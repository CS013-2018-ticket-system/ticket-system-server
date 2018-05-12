<div class="page" data-name="left_ticket" data-page="left_ticket">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">Back</span>
                </a>
            </div>
            <div class="title">车次查询</div>
        </div>
    </div>
    <input id="_csrf" type="text" value="{{ csrf_token() }}" hidden/>
    <div class="page-content">
        <div class="list no-hairlines-md">
            <ul>
                <li>
                    <div class="row">
                        <div class="item-content item-input col-24">
                            <div class="item-inner">
                                <div class="item-title item-label">出发站</div>
                                <div class="item-input-wrap">
                                    <input id="departure" type="text" placeholder="Departure"/>
                                </div>
                            </div>
                        </div>
                        <div class="item-content item-input col-24">
                            <div class="item-inner">
                                <div class="item-title item-label">到达站</div>
                                <div class="item-input-wrap">
                                    <input id="arrival" type="text" placeholder="Arrival"/>
                                </div>
                            </div>
                        </div>
                        <div class="item-content item-input col-30">
                            <div class="item-inner">
                                <div class="item-title item-label">出发日期</div>
                                <div class="item-input-wrap">
                                    <input type="text" placeholder="Departure Date" readonly="readonly" id="departure_date"/>
                                </div>
                            </div>
                        </div>
                        <div class="item-content item-input col-16">
                            <button class="col button button-outline" id="search-button">查询</button>
                        </div>
                        <div class="item-content item-input col-6">
                            <!-- 占位 -->
                        </div>
                    </div>
                </li>
            </ul>

            <div class="data-table">
                <table>
                    <thead>
                    <tr>
                        <th class="label-cell sortable-cell">车次</th>
                        <th class="label-cell sortable-cell">预定座位</th>
                        <th class="label-cell sortable-cell">出发站</th>
                        <th class="label-cell sortable-cell">到达站</th>
                    </tr>
                    </thead>
                    <tbody id="trains_data">

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>