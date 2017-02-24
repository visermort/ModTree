
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

Array.from(document.getElementsByClassName("mod-tree_seach-button")).forEach(
    function(element, index, array) {
        element.onclick = searchResources;
    }
);
Array.from(document.getElementsByClassName("mod-tree__paginate-button")).forEach(
    function(element, index, array) {
        element.onclick = searchResources;
    }
);

//при открытии - закрытии узла
function iconClick(e) {
    var element = getParentTargetElement(e.target, 'mod-tree__item-icon', true),
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
//нажатие на титле у узла
function titleClick(e) {
    var element = getParentTargetElement(e.target, 'mod-tree__item-title', true),
        li = getParentTargetElement(element, 'mod-tree__item', false),
        //ul  = parent.parentElement,
        //url = ul.getAttribute('data-url'),
        url = 'modtreeajax.php',
        data = 'id='+li.getAttribute('data-id')+
            '&cts=web'+
            '&action=web/resource/get',
        action = 'web/resource/get';

    //приготовили данные и сделали запрос, при завершении функция
    httpRequest(element, url, action, data, showObject);
}


//при завершении запроса при клике на титле узла - вывод данных объекта в шаблон
function showObject(element, data) {
    //получили данные, меняем в шаблоне
    var idPrefix = getParentTargetElement(element, 'mod-tree__list', true).getAttribute('data-content-id-prefix');
    replaceResourceData(data, idPrefix);
    removeActiveNodes();
    element.classList.add('active');
}

//запрос на дочерние элементы узла
function getItemChildData(element) {
    var li = getParentTargetElement(element ,'mod-tree__item', true),
        ul  = getParentTargetElement(li, 'mod-tree__list', false),
//        url = ul.getAttribute('data-url'),
        url = 'modtreeajax.php',
        data = 'id='+li.getAttribute('data-id')+
            '&limit='+ul.getAttribute('data-limit')+
            '&sortBy='+ul.getAttribute('data-sortby')+
            '&sortDir='+ul.getAttribute('data-sortDir')+
            '&linkWay='+ul.getAttribute('data-linkWay')+
            '&queryLinks=1'+
            '&action=web/resource/getlist',
        action = 'web/resource/getlist';
    //приготовили данные и сделали запрос, при завершении функция
    httpRequest(element, url, action, data, makeChildNodes);
}

//при завершении запроса на дочерние ресурсы - создание дочерних узлов
function makeChildNodes(element, data) {
   // console.log(data);
    if (data.items.length > 0) {
        var tree = getParentTargetElement(element, 'mod-tree__tree', true),
            hidden = document.getElementsByClassName('mod-tree__tree-templates')[0],
            li = getParentTargetElement(element, 'mod-tree__item'),
            ul  = getParentTargetElement(li, 'mod-tree__list', false),
            ulNew = ul.cloneNode(true),
            liTemplate = hidden.getElementsByClassName('mod-tree__item-tree')[0],//.cloneNode(true),
            //liTemplate = parent.cloneNode(true),
            content = li.getElementsByClassName('mod-tree__item-content');
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
    var button = getParentTargetElement(e.target, 'mod-tree__run-search', true),
        fieldsDiv = getParentTargetElement(button, 'mod-tree__tree', true).getElementsByClassName('mod-tree__seach')[0],
        fields = fieldsDiv.getElementsByClassName('mod-tree__search-fields-item-field'),
        params = [];
    //console.log(fieldsDiv, fields);
    Array.from(fields).forEach(function(field, key) {
        name = field.getAttribute('name');
        value = field.value;
        //console.log(name, value);
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
                '&page='+button.getAttribute('data-page')+
                '&queryLinks='+fieldsDiv.getAttribute('data-query-links')+
                '&id='+fieldsDiv.getAttribute('data-id'),
            url = 'modtreeajax.php',
            action = 'web/resource/getlist';
       // console.log(data, url, action);
        //делаем запрос
        httpRequest(button, url, action, data, makeSearchList);
}

//после поиска, отображение результатов
function makeSearchList(element, data) {
    //console.log(data);
    var tree = getParentTargetElement(element, 'mod-tree__tree', false),
        hidden = document.getElementsByClassName('mod-tree__tree-templates')[0],
        liTemplate = hidden.getElementsByClassName('mod-tree__item-list')[0].cloneNode(true),
        ul = tree.getElementsByClassName('mod-tree__list')[0],
        paginate = tree.getElementsByClassName('mod-tree__paginate')[0],
        searchResult = tree.getElementsByClassName('mod-tree__search-result')[0];
    //console.log(element, liTemplate, ul);
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
        //console.log('buttons');
        if (data.pagination.buttons != null && data.pagination.buttons.length > 1) {
            data.pagination.buttons.forEach(function(item, index){
                //console.log('button',item);
                var button = hidden.getElementsByClassName('mod-tree__paginate-button')[0].cloneNode(true);
                button.classList.remove('mod-tree__paginate-button-template');
                button.classList.remove('hidden');
                //button.innerHTML = item.page;
                button.setAttribute('data-page', item.page);
                replaceItemData(button, {'page': item.page});
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
    //var preloader = document.getElementById('floatingCirclesG');
    //var preloaderWrapper = getParentTargetElement(element, 'floatingBarsG-wrapper', true);
    var preloaderWrapper = getPreloaderWrapper(element);
    if (preloaderWrapper) {
        var preloader = preloaderWrapper.getElementsByClassName('floatingBarsG')[0];
    }
    xhr = new XMLHttpRequest();
    xhr.open('POST', url);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.setRequestHeader('Action', action);
    xhr.responseType='json';
    xhr.upload.onprogress = function(){
        showPreloader(preloaderWrapper, preloader, true);
    };
    xhr.send(data);

    xhr.onload = xhr.onerror = function () {
//        preloader.style.display = 'none';
        showPreloader(preloaderWrapper, preloader, false);
        var response = xhr.response;
        //console.log(response);
        if (response.object != null) {
            //получили данные, вызываем функцию
            onLoad(element, response.object);
        } else {
            //console.log(response);
        }
    };
}

//вспомогательное
//находим обёртку прелоудера - рядом с элементом
function getPreloaderWrapper(element) {
    var parent = getParentTargetElement(element, 'floatingBarsG-parent', true);
    if (parent) {
        if (parent.classList.contains('floatingBarsG-wrapper')) {
            return parent;
        }
        wrapper = parent.getElementsByClassName('floatingBarsG-wrapper')[0];
    }
    return wrapper;
}

function showPreloader(wrapper, preloader, show) {
    if (show) {
        if (wrapper) {
            wrapper.classList.add('loading');
        }
        if (preloader) {
            preloader.classList.remove('hidden');
        }
    } else {
        if (wrapper) {
            wrapper.classList.remove('loading');
        }
        if (preloader) {
            preloader.classList.add('hidden');
        }
    }
}


//поиск родительского узла с нужным классом

function getParentTargetElement(element, className, self){
    if (self == true) {
        target = element;
    } else {
        target = element.parentElement;
    }
    while (target != null  && !target.classList.contains(className)) {
        target = target.parentElement;

    }
    return target;
}

//открыть узел - назначение классов
function openItem(element) {
    li = getParentTargetElement(element, 'mod-tree__item', true);
    li.classList.remove('closed');
    li.classList.add('open');
    element.classList.remove('closed');
    element.classList.add('open');
}

//закрыть узел - назначение классов
function closeItem(element) {
    li = getParentTargetElement(element, 'mod-tree__item', true);
    li.classList.remove('open');
    li.classList.add('closed');
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
    var li = getParentTargetElement(element, 'mod-tree__item', true),
        id = li.getAttribute('data-id'),
        //grandParentLi = getParents(li, 6),
        parentLi = getParentTargetElement(li, 'mod-tree__item', false);
    if (parentLi == null) {
        return true;
    }
    var grandParentLi = getParentTargetElement(parentLi, 'mod-tree__item', false);
    if (grandParentLi == null) {
        return true;
    }
    var id2 = grandParentLi.getAttribute('data-id');
    if (id != id2) {
        return true;
    }
    element.classList.remove('promised');
    element.classList.add('leaf');
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
function replaceResourceData(data, idPrefix) {

    for (var key in data) {
        element = document.getElementById(idPrefix+key);
        if (element != null) {
            if (key.substring(0, 3) == 'uri') {
                //обрабатываем ссылки - особый случай
                element.setAttribute('href', data[key]);
                element.classList.remove('hidden');
            } else if ((key.substring(0, 5) == 'image')) {
                element.setAttribute('src', data[key]);
                element.classList.remove('hidden');
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