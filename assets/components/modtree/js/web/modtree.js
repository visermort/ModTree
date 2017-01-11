
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

function titleClick(e) {

    var element = getParentTargetElement(e.target, 'mod-tree__item-title'),
        parent = element.parentElement,
        ul  = parent.parentElement,
        url = ul.getAttribute('data-url'),
        data = 'id='+parent.getAttribute('data-id'),
        action = 'web/resource/get';

    // console.log(element, parent, url, action, data);
    xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('Action', action);
    xhr.responseType='json';
    xhr.send(data);
    xhr.onload = function () {
        var response = xhr.response;
        //console.log(response);
        if (response.object != null) {
            //получили данные, меняем в шаблоне
            var contentElement = document.getElementById('mod-tree-content');
            replaceData(contentElement, response.object);
            removeActiveNodes();
            element.classList.add('active');
        } else {
            console.log(response);
        }
    };
}

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
    //  console.log(url, action, data);
    xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('Action', action);
    xhr.responseType='json';
    xhr.send(data);
    xhr.onload = function (){

        var response = xhr.response,
            ulNew = ul.cloneNode(true),
            liTemplate = parent.cloneNode(true),
            content = parent.getElementsByClassName('mod-tree__item-content');
        //console.log(response);
        if (response.object != null) {
            ulNew.innerHTML = '';
            //берём родительлкий ul, очищаем от содержимого и вставлямв в нижний блок
            content[0].append(ulNew);
            response.object.forEach(function(item, index){
                //берём родительский li , в цикле вставляем во новый ul, подменяя значения
                liNew = liTemplate.cloneNode(true);
                //console.log('liNew', liNew);
                liNew.setAttribute('data-id', item.id);
                replaceData(liNew, item);
                ulNew.append(liNew);
                //событие на клик
                liNew.getElementsByClassName('mod-tree__item-icon')[0].onclick = iconClick;
                liNew.getElementsByClassName('mod-tree__item-title')[0].onclick = titleClick;
            });
            element.classList.remove('promised');
            element.classList.add('open');

        } else {
            console.log(response);
        }

    };
}

function getParentTargetElement(element, className){
    target = element;
    while (!target.classList.contains(className)) {
        target = target.parentElement;
    }
    return target;
}

function openItem(element) {
    parent = element.parentElement;
    parent.classList.remove('closed');
    parent.classList.add('open');
    element.classList.remove('closed');
    element.classList.add('open');
}

function closeItem(element) {
    parent = element.parentElement;
    parent.classList.remove('open');
    parent.classList.add('closed');
    element.classList.remove('open');
    element.classList.add('closed');
}

function removeActiveNodes(){
    Array.from(document.getElementsByClassName("mod-tree__item-title")).forEach(
        function(element, index, array) {
            element.classList.remove('active');
        }
    );
}

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

function replaceData(element, data) {
    if (element != null && element.nodeType == 1) { //если тип - элемент, делаем замену содержимого или для каждого дочернего  вызываем снова себя
        dataName = element.getAttribute('data-name');
        // console.log(dataName);
        if (dataName != null) {
            // console.log(dataName);
            // console.log(dataName.substring(0, 3));
            if (dataName.substring(0, 3) == 'uri') {
                //обрабатываем ссылки
                if (data[dataName]) {
                    a = element.getElementsByTagName('a')[0];
                    a.setAttribute('href', data[dataName]);
                    a.innerHTML = data[dataName];
                } else {
                    element.innerHTML = data[dataName];
                }

            } else if ((dataName.substring(0, 5) == 'image')){
                //oбрабатываем image
                if (data[dataName]) {
                    a = element.getElementsByTagName('img')[0];
                    if (a != null) {
                        a.setAttribute('src', data[dataName]);
                    }
                } else {
                    element.innerHTML = data[dataName];
                }
            } else {
                element.innerHTML = data[dataName];
            }
        } else {
            for (var i = 0; i < element.childNodes.length; i++) {
                replaceData(element.childNodes[i], data);
            }
        }
    }
}