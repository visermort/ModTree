<div class="mod-tree container clearfix">
    [[-tree]]
    <div class="mod-tree__tree">
        <div class="mod-tree__panel">
            <div class="mod-tree__seach"
                data-limit="[[+limitList]]"
                data-linkway="[[+linkWay]]"
                data-sortby="[[+sortBy]]"
                data-sortdir="[[+sortDir]]"
                data-paginate-list="[[+paginateList]]">

                [[+searchfields:ne=``:then=`
                    <ul class="mod-tree__seach-fields">[[+searchfields]]</ul>
                    <div class="mod-tree_seach-buttons">
                        <button data-page="0" class="mod-tree_seach-button button">Поиск</button>
                    </div>
                `]]
            </div>

            <div class="mod-tree__tree-templates hidden">
                <ul>
                    [[+itemHiddenList]]
                    [[+itemHiddenTree]]
                </ul>
                <button  class="mod-tree__paginate-button mod-tree__paginate-button-template"></button>
            </div>
        </div>
        [[-list after search of after page load]]
        <div class="mod-tree__panel">
            <ul  class="mod-tree__list hidden"
                data-limit="[[+limit]]"
                data-linkway="[[+linkWay]]"
                data-sortby="[[+sortBy]]"
                data-sortdir="[[+sortDir]]">
                [[+items]]
            </ul>
            <div class="mod-tree__paginate hidden">
            </div>
            <div class="mod-tree__search-result [[+limitList:is=`0`:or:if=`[[+countResult]]`:is=`0`:then=`hidden`]]">
                Записей
                <span data-name="countResult"> [[+countResult]] </span>
                из
                <span data-name="count"> [[+count]] </span>
                .Страница
                <span data-name="page"> [[+page]] </span>
                из
                <span data-name="pages"> [[+pages]]</span>
            </div>
        </div>

    </div>
    [[-resource content]]
    <div class="mod-tree__content mod-tree__panel"  >
        <h4 id="modtree-pagetitle" class="mod-tree__content-header"> </h4>
        <div class="mod-tree__content-description">
            <div class="mod-tree__content-publishedon" id="modtree-publishedon"></div>
            <div class="mod-tree__content-introtext" id="modtree-intotext"></div>
        </div>

        <div class="mod-tree__content-contenttext">
            <div class="mod-tree__content-text">
                <div class="mod-tree__content-content" id="modtree-content"></div>
            </div>
            <div class="mod-tree__content-image" >
                <img id="modtree-image" src="" >
            </div>
        </div>
        <div class="mod-tree__content-uri"><a id="modtree-uri" href=""><span id="modtree-urltext"></span></a></div>
    </div>
</div>
[[-preloader]]
<div id="floatingCirclesG">
    <div class="f_circleG" id="frotateG_01"></div>
    <div class="f_circleG" id="frotateG_02"></div>
    <div class="f_circleG" id="frotateG_03"></div>
    <div class="f_circleG" id="frotateG_04"></div>
    <div class="f_circleG" id="frotateG_05"></div>
    <div class="f_circleG" id="frotateG_06"></div>
    <div class="f_circleG" id="frotateG_07"></div>
    <div class="f_circleG" id="frotateG_08"></div>
</div>
