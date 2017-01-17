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
// var button = document.getElementById('mod-tree_seach-button');
//     if (button !=null ) {
//         button.onclick  = searchResources;
//     }
Array.from(document.getElementsByClassName("mod-tree_seach-button")).forEach(
    function(element, index, array) {
        element.onclick = searchResources;
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
        //url = ul.getAttribute('data-url'),
        url = 'modtreeajax.php',
        data = 'id='+parent.getAttribute('data-id')+
            '&cts=web'+
            '&action=web/resource/get',
        action = 'web/resource/get';

    //приготовили данные и сделали запрос, при завершении функция
    httpRequest(element, url, action, data, showObject);
}


//при завершении запроса при клике на титле узла - вывод данных объекта в шаблон
function showObject(element, data) {
    //получили данные, меняем в шаблоне
    replaceResourceData(data);
    removeActiveNodes();
    element.classList.add('active');
}

//запрос на дочерние элементы узла
function getItemChildData(element) {
    var parent = element.parentElement,
        ul  = parent.parentElement,
//        url = ul.getAttribute('data-url'),
        url = 'modtreeajax.php',
        data = 'id='+parent.getAttribute('data-id')+
            '&limit='+ul.getAttribute('data-limit')+
            '&sortBy='+ul.getAttribute('data-sortby')+
            '&sortDir='+ul.getAttribute('data-sortDir')+
            '&linkWay='+ul.getAttribute('data-linkWay')+
            '&cts=web'+
            '&action=web/tree/getlist',
        action = 'web/tree/getlist';
    //приготовили данные и сделали запрос, при завершении функция
    httpRequest(element, url, action, data, makeChildNodes);
}

//при завершении запроса на дочерние ресурсы - создание дочерних узлов
function makeChildNodes(element, data) {
    console.log(data);
    if (data.items.length > 0) {
        var tree = getParentTargetElement(element, 'mod-tree__tree'),
            hidden = document.getElementsByClassName('mod-tree__tree-templates')[0],
            parent = element.parentElement,
            ul = parent.parentElement,
            ulNew = ul.cloneNode(true),
            liTemplate = hidden.getElementsByClassName('mod-tree__item-tree')[0],//.cloneNode(true),
            //liTemplate = parent.cloneNode(true),
            content = parent.getElementsByClassName('mod-tree__item-content');
        ulNew.innerHTML = '';
        liTemplate.getElementsByClassName('mod-tree__item-title')[0].classList.remove('active');
        content[0].append(ulNew);
        data.items.forEach(function (item, index) {
            //берём родительский li , в цикле вставляем в новый ul, заменяя значения
            liNew = liTemplate.cloneNode(true);
            liNew.setAttribute('data-id', item.id);
            replaceItemData(liNew, item);
            ulNew.append(liNew);
            //события на клик
            liNew.getElementsByClassName('mod-tree__item-icon')[0].onclick = iconClick;
            liNew.getElementsByClassName('mod-tree__item-title')[0].onclick = titleClick;
        });
        element.classList.remove('promised');
        element.classList.add('open');
    } else {
        element.classList.remove('promised');
        element.classList.add('leaf');
    }
}

//нажатие на "Поиск" и на конопки пагинации
function searchResources(e) {
    var button = e.target,
        fieldsDiv = getParentTargetElement(button, 'mod-tree__tree').getElementsByClassName('mod-tree__seach')[0],
        fields = fieldsDiv.getElementsByClassName('mod-tree__search-fields-item-field'),
        params = [];
    console.log(fieldsDiv, fields);
    Array.from(fields).forEach(function(field, key) {
        name = field.getAttribute('name');
        value = field.value;
        console.log(name, value);
        if (value != "") {
            params.push({ name: name, value: value});
        }
    });
    //console.log(params);
    //console.log(JSON.stringify(params));
   // console.log(params.toJSON());
        var data = '&limit='+fieldsDiv.getAttribute('data-limit')+
                '&sortBy='+fieldsDiv.getAttribute('data-sortby')+
                '&sortDir='+fieldsDiv.getAttribute('data-sortDir')+
                '&linkWay='+fieldsDiv.getAttribute('data-linkWay')+
                '&searchParams='+JSON.stringify(params)+
                 '&action=web/resource/getlist'+
                '&paginateList='+fieldsDiv.getAttribute('data-paginate-list')+
                '&page='+button.getAttribute('data-page'),
            url = 'modtreeajax.php',
            action = 'web/resource/getlist';
        console.log(data, url, action);
        //делаем запрос
        httpRequest(button, url, action, data, makeSearchList);
}

//после поиска, отображение результатов
function makeSearchList(element, data) {
    console.log(data);
    var tree = getParentTargetElement(element, 'mod-tree__tree'),
        hidden = document.getElementsByClassName('mod-tree__tree-templates')[0],
        liTemplate = hidden.getElementsByClassName('mod-tree__item-list')[0].cloneNode(true),
        ul = tree.getElementsByClassName('mod-tree__list')[0],
        paginate = tree.getElementsByClassName('mod-tree__paginate')[0],
        searchResult = tree.getElementsByClassName('mod-tree__search-result')[0];
    console.log(element, liTemplate, ul);
    ul.innerHTML = '';
    paginate.innerHTML = '';
    if (data.items.length > 0) {
        ul.classList.remove('hidden');
        data.items.forEach(function (item, index) {
            //берём родительский li , в цикле вставляем в новый ul, заменяя значения
            var liNew = liTemplate.cloneNode(true);
            liNew.setAttribute('data-id', item.id);
            replaceItemData(liNew, item);
            ul.append(liNew);
            //события на клик
            liNew.getElementsByClassName('mod-tree__item-icon')[0].onclick = iconClick;
            liNew.getElementsByClassName('mod-tree__item-title')[0].onclick = titleClick;
        });
        console.log('buttons');
        if (data.pagination.buttons != null && data.pagination.buttons.length > 1) {
            data.pagination.buttons.forEach(function(item, index){
                console.log('button',item);
                var button = tree.getElementsByClassName('mod-tree__paginate-button-template')[0].cloneNode(true);
                button.classList.remove('mod-tree__paginate-button-template');
                button.classList.remove('hidden');
                button.innerHTML = item.page;
                button.setAttribute('data-page', item.page);
                if (item.current == true) {
                    button.classList.add('current');
                    //button.setAttribute('disabled', true);
                    button.disabled = true;
                } else {
                    button.onclick = searchResources;    
                }
                paginate.append(button);
            });
            paginate.classList.remove('hidden');
        }
        makeSeachResult(searchResult, data.pagination);

    } else {
        ul.classList.add('hidden');
        paginate.classList.add('hidden');
        searchResult.classList.add('hidden');
    }
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
        console.log(response);
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

//замена данных в элементе дерева
function replaceItemData(element, data){
    if (element != null && element.nodeType == 1) {
        //если тип - элемент, делаем замену содержимого или для каждого дочернего  вызываем снова себя
        dataName = element.getAttribute('data-name');
        if (dataName != null) {
            element.innerHTML = data[dataName];
        }

        for (var i = 0; i < element.childNodes.length; i++) {
            replaceItemData(element.childNodes[i], data);
        }

    }
}

//замена данных в шаблоне ресурса
function replaceResourceData(data) {

    for (var key in data) {
        element = document.getElementById('modtree-'+key);
        if (element != null) {
            if (key.substring(0, 3) == 'uri') {
                //обрабатываем ссылки - особый случай
                element.setAttribute('href', data[key]);
            } else if ((key.substring(0, 5) == 'image')) {
                element.setAttribute('src', data[key]);
            } else {
                element.innerHTML = data[key];
            }
        }
    }

}

//текстовое отображение результатов поиска
function makeSeachResult(element, data)
{
    if (data.limit==0 || data.searchResult == 0){
        element.classList.add('hidden');
    } else {
        replaceItemData(element, data);
        element.classList.remove('hidden');
    }
}