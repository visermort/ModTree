<div class="mod-tree container clearfix">
    [[   -tree    ]]
    <div class="mod-tree__tree">
        <div class="mod-tree__panel [[+searchfields:is=``:then=`hidden`]]">
            <div class="mod-tree__seach"
                data-id="[[+id]]"
                data-limit="[[+limitList]]"
                data-linkway="[[+linkWay]]"
                data-sortby="[[+sortBy]]"
                data-sortdir="[[+sortDir]]"
                data-paginate-list="[[+paginateList]]"
                data-query-links="[[+queryLinks]]"
            >
                [[-   search fields    ]]
                [[+searchfields:ne=``:then=`
                    <ul class="mod-tree__seach-fields">[[+searchfields]]</ul>
                    <div class="mod-tree_seach-buttons preloader-parent">
                        <button data-page="0" class="mod-tree_seach-button mod-tree__run-search button preloader-wrapper">
                                <img class="mod-tree_seach-button-preloader preloader-gif hidden" src="/assets/components/modtree/img/loader.gif" alt="">
                            [[%modtree_chunk_search]]
                        </button>
                    </div>
                `]]
            </div>
            [[-     hidden templates  ]]
            <div class="mod-tree__tree-templates hidden">
                <ul>
                    [[+itemHiddenList]]
                    [[+itemHiddenTree]]
                </ul>
                    [[+buttonHidden]]
            </div>
        </div>
        [[-          list after search or after page load         ]]
        <div class="mod-tree__panel">
            <ul  class="mod-tree__list [[+items:is=``:then=`hidden`]]"
                data-limit="[[+limit]]"
                data-linkway="[[+linkWay]]"
                data-sortby="[[+sortBy]]"
                data-sortdir="[[+sortDir]]"
                data-content-id-prefix="[[+contentIdPrefix]]">
                [[+items]]
            </ul>
            <div class="mod-tree__paginate [[+buttons:is=``:then=`hidden`]]">
                [[+buttons]]
            </div>
            <div class="mod-tree__search-result [[+limitList:is=`0`:or:if=`[[+pagination.countResult]]`:is=`0`:then=`hidden`]]">
                [[%modtree_chunk_record]]
                <span data-name="countResult"> [[+pagination.countResult]] </span>
                [[%modtree_chunk_from]]
                <span data-name="count"> [[+pagination.count]] </span>
                .[[%modtree_chunk_page]]
                <span data-name="page"> [[+pagination.page]] </span>
                [[%modtree_chunk_from]]
                <span data-name="pages"> [[+pagination.pages]]</span>
            </div>
        </div>

    </div>
    [[-     resource content            ]]
    <div class="mod-tree__content mod-tree__panel"  >
        <h4 id="modtree-pagetitle" class="mod-tree__content-header"> </h4>
        <div class="mod-tree__content-description">
            <div class="mod-tree__content-publishedon" id="modtree-publishedon"></div>
            <div class="mod-tree__content-introtext" id="modtree-introtext"></div>
        </div>

        <div class="mod-tree__content-contenttext">
            <div class="mod-tree__content-text">
                <div class="mod-tree__content-content" id="modtree-content"></div>
            </div>
            <div class="mod-tree__content-image" >
                <img id="modtree-image" class="hidden mod-tree__content-image" src="" />
            </div>
        </div>
        <div class="mod-tree__content-uri "><a id="modtree-uri" href="" class="hidden">[[%modtree_chunk_readmore]]</a></div>
    </div>
</div>


