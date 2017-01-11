//при отрытии документа - события: отрыть узел, кликнцть на титле в узле
Array.from(document.getElementsByClassName("mod-tree__item-icon")).forEach(
    function(element, index, array) {
        element.onclick = iconClick;
    }
);
Array.from(document.getElementsByClassName("mod-tree__item-title")).forEach(
    function(element, index, array) {
        element.onclick = titleClick;
    }
);

//при открытии - закрытии узла
function iconClick(e) {
    var element = getParentTargetElement(e.target, 'mod-tree__item-icon'),
        classes = element.classList;
    if (classes.contains('promised')) {
        if (checkRepeated(element)) {
            getItemChildData(element);
        }
    } else if (classes.contains('open')) {
        closeItem(element);
    } else if (classes.contains('closed')) {
        openItem(element);
    }
}
//нажание на титле у узла
function titleClick(e) {
    var element = getParentTargetElement(e.target, 'mod-tree__item-title'),
        parent = element.parentElement,
        ul  = parent.parentElement,
        url = ul.getAttribute('data-url'),
        data = 'id='+parent.getAttribute('data-id'),
        action = 'web/resource/get';

    //приготовили данные и сделали запрос, при завершении функция
    httpRequest(element, url, action, data, showObject);
}


//при завершении запроса при клике на титле узла - вывод данных объекта в шаблон
function showObject(element, data) {
    //получили данные, меняем в шаблоне
    var contentElement = document.getElementById('mod-tree-content');
    replaceData(contentElement, data);
    removeActiveNodes();
    element.classList.add('active');
}

//запрос на дочерние элементы узла
function getItemChildData(element) {
    var parent = element.parentElement,
        ul  = parent.parentElement,
        url = ul.getAttribute('data-url'),
        data = 'id='+parent.getAttribute('data-id')+
            '&limit='+ul.getAttribute('data-limit')+
            '&sortBy='+ul.getAttribute('data-sortby')+
            '&sortDir='+ul.getAttribute('data-sortDir')+
            '&linkWay='+ul.getAttribute('data-linkWay'),
        action = 'web/tree/getlist';
    //приготовили данные и сделали запрос, при завершении функция
    httpRequest(element, url, action, data, makeChildNodes);
}

//при завершении запроса на дочерние ресурсы - создание дочерних узлов
function makeChildNodes(element, data) {
    var parent = element.parentElement,
        ul  = parent.parentElement,
        ulNew = ul.cloneNode(true),
        liTemplate = parent.cloneNode(true),
        content = parent.getElementsByClassName('mod-tree__item-content');
    ulNew.innerHTML = '';
    liTemplate.getElementsByClassName('mod-tree__item-title')[0].classList.remove('active');
    content[0].append(ulNew);
    data.forEach(function(item, index){
        //берём родительский li , в цикле вставляем в новый ul, заменяя значения
        liNew = liTemplate.cloneNode(true);
        //console.log('liNew', liNew);
        liNew.setAttribute('data-id', item.id);
        replaceData(liNew, item);
        ulNew.append(liNew);
        //события на клик
        liNew.getElementsByClassName('mod-tree__item-icon')[0].onclick = iconClick;
        liNew.getElementsByClassName('mod-tree__item-title')[0].onclick = titleClick;
    });
    element.classList.remove('promised');
    element.classList.add('open');
}

//айакс - запрос - общий
function httpRequest(element, url, action, data, onLoad){
    var preloader = document.getElementById('floatingCirclesG');
    xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('Action', action);
    xhr.responseType='json';
    xhr.upload.onprogress = function(){
        preloader.style.display = 'block';
    };
    xhr.send(data);

    xhr.onload = xhr.onerror = function () {
        preloader.style.display = 'none';
        var response = xhr.response;
        //console.log(response);
        if (response.object != null) {
            //получили данные, вызываем функцию
            onLoad(element, response.object);
        } else {
            console.log(response);
        }
    };
}

//вспомогательное

//поиск родительского узла с нужным классом
function getParentTargetElement(element, className){
    target = element;
    while (!target.classList.contains(className)) {
        target = target.parentElement;
    }
    return target;
}

//открыть узел - назначение классов
function openItem(element) {
    parent = element.parentElement;
    parent.classList.remove('closed');
    parent.classList.add('open');
    element.classList.remove('closed');
    element.classList.add('open');
}

//закрыть узел - назначение классов
function closeItem(element) {
    parent = element.parentElement;
    parent.classList.remove('open');
    parent.classList.add('closed');
    element.classList.remove('open');
    element.classList.add('closed');
}

//снатие признака активности у всех узлов
function removeActiveNodes(){
    Array.from(document.getElementsByClassName("mod-tree__item-title")).forEach(
        function(element, index, array) {
            element.classList.remove('active');
        }
    );
}

//проверка узла, что второй родительский - тот же ресурс
function checkRepeated(element) {
    var li = element.parentElement,
        id = li.getAttribute('data-id'),
        grandParentLi = getParents(li, 6);
    if (grandParentLi == null) {
        return true;
    }
    id2 = grandParentLi.getAttribute('data-id');
    if (id != id2) {
        return true;
    }
    element.classList.remove('promised');
    element.classList.add('leaf');
}

//нахождение родительского узла нужного уровня
function getParents(element, level) {
    var parent = element;
    for (var i = 0; i < level; i++) {
        parent = parent.parentElement;
        if (parent == null) {
            return null;
        }
    }
    return parent;
}

//замена данных в шаблоне
function replaceData(element, data) {
    if (element != null && element.nodeType == 1) {
        //если тип - элемент, делаем замену содержимого или для каждого дочернего  вызываем снова себя
        dataName = element.getAttribute('data-name');
        if (dataName != null) {
            if (dataName.substring(0, 3) == 'uri') {
                //обрабатываем ссылки - особый случай
                if (data[dataName]) {
                    a = element.getElementsByTagName('a')[0];
                    a.setAttribute('href', data[dataName]);
                    a.innerHTML = data[dataName];
                } else {
                    element.innerHTML = data[dataName];
                }

            } else if ((dataName.substring(0, 5) == 'image')){
                //oбрабатываем image - особый случай
                if (data[dataName]) {
                    a = element.getElementsByTagName('img')[0];
                    if (a != null) {
                        a.setAttribute('src', data[dataName]);
                    }
                } else {
                    element.innerHTML = data[dataName];
                }
            } else {
                //для остального просто вставляем содержимое
                element.innerHTML = data[dataName];
            }
        } else {
            for (var i = 0; i < element.childNodes.length; i++) {
                replaceData(element.childNodes[i], data);
            }
        }
    }
}