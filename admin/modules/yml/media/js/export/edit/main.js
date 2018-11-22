$(document).ready(function () {
    $("#export-catalog > span.dotted").on("click", function () {
        var _this = $(this);
        var _block = _this.next();

        if (_this.data("expand") === false) {

            $.post(
                    {
                        url: "/admin/yml/export/items-json",
                        data: {
                            category_id: _this.data("category-id"),
                            brand_id: _this.data("brand-id"),
                        },
                        dataType: "json",
                        success: function (data) {
                            if (data.status == "success") {
                                $.each(data.items, function (i, item) {
                                    _block.append("&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=\"checkbox\" name=\"Export[items][]\" value=" + item["id"] + ">" + item["name"] + " " + item["article"] + "<br>");
                                });
                            }
                        },
                    },
                    );
                    _this.data("expand", "true");
                    
        }
    });
});