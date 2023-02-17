$(".select2_init_multiple").select2({
    // placeholder: "Chọn",
    // tags: true,
    multiple: true
});

$(".select2_init_multiple_tag").select2({
    // placeholder: "Chọn",
    tags: true,
    multiple: true
});

$(".select2_init").select2({
    placeholder: "Chọn",
    // allowClear: true
});

$(".select2_init_allow_clear").select2({
    placeholder: "Chọn",
    allowClear: true
});

$(".select2_init_tag").select2({
    placeholder: "Chọn",
    tags: true,
    // allowClear: true
});

function tryParseInt(val) {
    try {
        val = val.toString()
        return parseInt(val.replace(/,/g, "").replace(/$/g, "").replace(/đ/g, "")) || 0
    } catch (e) {
        return 0
    }
}

function tryParseFloat(val) {
    try {
        val = val.toString()
        return parseFloat(val.replace(/,/g, "").replace(/$/g, "").replace(/đ/g, "")) || 0
    } catch (e) {
        return 0
    }
}

function formatMoney(nStr) {
    nStr += ""
    x = nStr.split(",")
    x1 = x[0]
    x2 = x.length > 1 ? "," + x[1] : ""
    var rgx = /(\d+)(\d{3})/
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2")
    }
    return (x1 + x2).split(".")[0] + "đ"
}

const getOnlyTime = (dataD) => {
    let data = new Date(dataD)
    let hrs = data.getHours()
    let mins = data.getMinutes()
    if (hrs <= 9)
        hrs = '0' + hrs
    if (mins < 10)
        mins = '0' + mins
    const postTime = hrs + ':' + mins
    return postTime
}

const getOnlyDate = (dataD, format = "dd/mm/yyyy") => {
    let dateObj = new Date(dataD)
    let myDate = addZero(dateObj.getUTCDate()) + "/" + addZero(dateObj.getMonth() + 1) + "/" + addZero(dateObj.getUTCFullYear());

    if (format == "yyyy/mm/dd") {
        myDate = addZero(dateObj.getUTCFullYear()) + "/" + addZero(dateObj.getMonth() + 1) + "/" + addZero(dateObj.getUTCDate());
    } else if (format == "mm/dd/yyyy") {
        myDate = addZero(dateObj.getMonth() + 1) + "/" + addZero(dateObj.getUTCDate()) + "/" + addZero(dateObj.getUTCFullYear());
    }

    return myDate;
}

function addZero(input) {
    if ((input + "").length <= 1) {
        return '0' + input
    }
    return input
}

function getFormattedDate(input, format = "dd/mm/yyyy") {
    // var date = new Date(input)
    // var year = date.getFullYear();
    //
    // var month = (1 + date.getMonth()).toString();
    // month = month.length > 1 ? month : '0' + month;
    //
    // var day = date.getDate().toString();
    // day = day.length > 1 ? day : '0' + day;
    //
    // return month + '/' + day + '/' + year;

    return getOnlyDate(input, format) + " " + getOnlyTime(input)
}

function actionDelete(event, url = null, table = null, target_remove = null) {
    event.preventDefault()
    let urlRequest = $(this).data('url')
    let that = $(this)

    if (!urlRequest) {
        urlRequest = url
    }

    Swal.fire({
        title: 'Bạn có chắc?',
        text: "Tác vụ sẽ không thể hoàn tác!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Vâng, xóa nó!',
        cancelButtonText: 'Không'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: urlRequest,
                beforeSend: function () {
                    showLoading()
                },
                success: function (response) {
                    hideLoading()
                    if (response.code === 200) {
                        // table
                        //     .row(target_remove)
                        //     .remove()
                        //     .draw();
                        that.parent().parent().remove()
                        // Swal.fire(
                        //     'Đã xóa!',
                        //     'Đã xóa bản ghi.',
                        //     'success'
                        // )
                    }
                },
                error: function (err) {
                    console.log(err)
                    hideLoading()
                    Swal.fire(
                        {
                            icon: 'error',
                            title: err.responseText,
                        }
                    );
                },
            })
        }
    })
}

let editor_config = {
    path_absolute : "/",
    selector: 'textarea.tinymce_editor_init',
    relative_urls: false,
    plugins: [
        "advlist autolink lists link image charmap print preview hr anchor pagebreak",
        "searchreplace wordcount visualblocks visualchars code fullscreen",
        "insertdatetime media nonbreaking save table directionality",
        "emoticons template paste textpattern"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
    file_picker_callback : function(callback, value, meta) {
        let x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
        let y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

        let cmsURL = editor_config.path_absolute + 'filemanager?editor=' + meta.fieldname;
        if (meta.filetype == 'image') {
            cmsURL = cmsURL + "&type=Images";
        } else {
            cmsURL = cmsURL + "&type=Files";
        }

        tinyMCE.activeEditor.windowManager.openUrl({
            url : cmsURL,
            title : 'Filemanager',
            width : x * 0.8,
            height : y * 0.8,
            resizable : "yes",
            close_previous : "no",
            onMessage: (api, message) => {
                callback(message.content);
            }
        });
    }
};

tinymce.init(editor_config);

function reinstallTinymce(){
    tinymce.init(editor_config);
}

function formatCommentTime(value) {
    const date = new Date(value).getTime();

    const a = new Date().getTime() - date;
    if (a < 60000) {
        return `${Math.floor(a / 1000)} giây trước`;
    } else if (a < 3600000) {
        return ` ${Math.floor(a / 60000)} phút trước`;
    } else if (a >= 3600000 && a < 86400000) {
        return ` ${Math.floor(a / 3600000)} giờ trước`;
    } else if (a >= 86400000 && a < 2592000000) {
        return ` ${Math.floor(a / 86400000)} ngày trước`;
    } else if (a >= 2592000000 && a < 31104000000) {
        return ` ${Math.floor(a / 2592000000)} tháng trước`;
    } else {
        return ` ${Math.floor(a / 31104000000)} năm trước`;
    }
}

const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"]

function getCookie(name) {
    function escape(s) {
        return s.replace(/([.*+?\^$(){}|\[\]\/\\])/g, "\\$1")
    }

    var match = document.cookie.match(RegExp("(?:^|;\\s*)" + escape(name) + "=([^;]*)"))
    return match ? match[1] : null
}

function removeCookie(name) {
    document.cookie = name + "=removed; expires=Thu, 18 Dec 2013 12:00:00 UTC; path=/"
}

String.prototype.replaceAllTxt = function replaceAll(search, replace) {
    return this.split(search).join(replace)
}

// if (window.location.href.length > window.location.origin.length + 1 && !getCookie("token") && !window.location.href.includes("profile")) {
//     window.location.replace("./../../../../")
// }

var entityMap = {
    "&": "&amp;",
    "<": "&lt;",
    ">": "&gt;",
    '"': "&quot;",
    "'": "&#39;",
    "/": "&#x2F;",
    "`": "&#x60;",
    "=": "&#x3D;",
}

function downloadDataUrlFromJavascript(filename, dataUrl) {
    // Construct the 'a' element
    var link = document.createElement("a")
    link.download = filename
    link.target = "_blank"

    // Construct the URI
    link.href = dataUrl
    document.body.appendChild(link)
    link.click()

    // Cleanup the DOM
    document.body.removeChild(link)
    delete link
}

function formatDatetime(datetime, timezone) {
    if (timezone) {
    } else {
        return new Date(datetime).toLocaleString()
    }
}

function rangerBetweenDatetimeMinutes(date1, date2) {
    return Math.floor(Math.abs(Date.parse(date1) - Date.parse(date2)) / (1000 * 60))
}

function rangerBetweenDatetimeSeconds(date1, date2) {
    return Math.floor(Math.abs(Date.parse(date1) - Date.parse(date2)) / 1000)
}

function getTimeNow(timezone) {
    if (timezone) {
        return new Date(timezone).toLocaleString("en-US")
    } else {
        return new Date().toLocaleString("en-US")
    }
}

function formatDateFormProfileVI(date) {
    if (isDefine(date) && date.length) {
        let day = date.split(" ")[0]
        let time = date.split(" ")[1].substring(0, 5) + "'"

        return time + " ngày " + day
    }

    return ""
}

function formatDateFormProfileResultVI(date) {
    if (isDefine(date) && date.length) {
        return "ngày " + date.split(" ")[0].split("/")[0] + " tháng " + date.split(" ")[0].split("/")[1] + " năm " + date.split(" ")[0].split("/")[2]
    }

    return ""
}

function formatDateFormProfileResultEN(date) {
    if (isDefine(date) && date.length) {
        let day = new Date(date.split(" ")[0].split("/")[2] + "-" + date.split(" ")[0].split("/")[1] + "-" + date.split(" ")[0].split("/")[0]).toGMTString()
        day = day.split(",")[1].trim().split(" ")[1] + " - " + day.split(",")[1].trim().split(" ")[0] + "<sup>th</sup> " + day.split(",")[1].trim().split(" ")[2]

        return day
    }

    return ""
}

function formatDateFormProfileEN(date) {
    if (isDefine(date) && date.length) {
        let day = new Date(date.split(" ")[0].split("/")[2] + "-" + date.split(" ")[0].split("/")[1] + "-" + date.split(" ")[0].split("/")[0]).toGMTString()
        let time = formatAMPM(new Date(date.split(" ")[0].split("/")[2] + "/" + date.split(" ")[0].split("/")[1] + "/" + date.split(" ")[0].split("/")[0] + " " + date.split(" ")[1]))

        day = day.split(",")[1].trim().split(" ")[1] + " - " + day.split(",")[1].trim().split(" ")[0] + "<sup>th</sup> " + day.split(",")[1].trim().split(" ")[2]

        if (time.length < 8) {
            time = "0" + time
        }
        return time + " - " + day
    }

    return ""
}

function getTimeAMPM(date) {
    //return date.split(' ')[0].split('/')[2] + '-' + date.split(' ')[0].split('/')[1] + '-' + date.split(' ')[0].split('/')[0] + ' ' + date.split(' ')[1]
    //return new Date(date.split(' ')[0].split('/')[2] + '-' + date.split(' ')[0].split('/')[1] + '-' + date.split(' ')[0].split('/')[0] + ' ' + date.split(' ')[1])

    let time = formatAMPM(new Date(date.split(" ")[0].split("/")[2] + "/" + date.split(" ")[0].split("/")[1] + "/" + date.split(" ")[0].split("/")[0] + " " + date.split(" ")[1]))

    if (time.length < 8) time = "0" + time

    return time
}

function formatAMPM(date) {
    let hours = date.getHours()
    let minutes = date.getMinutes()
    const ampm = hours >= 12 ? "PM" : "AM"

    hours %= 12
    //hours = hours || 12
    minutes = minutes < 10 ? `0${minutes}` : minutes

    const strTime = `${hours}:${minutes} ${ampm}`

    return strTime
}

function escapeHtml(string) {
    return String(string).replace(/[&<>"'`=\/]/g, function (s) {
        return entityMap[s];
    })
    //ENCODED FOR MAXIMUM SAFETY
}

function removeHTMLTags(html) {
    var regX = /(<([^>]+)>)/gi
    return html.replace(regX, "")
}

function getOnlyNumber(str) {
    var num = str.replace(/[^0-9]/g, "")
    return num
}

function nameMonthToNumber(val) {
    for (let i = 0; i < monthNames.length; i++) {
        if (monthNames[i].includes(val)) {
            if (i < 9) {
                return "0" + (i + 1)
            }
            return "" + i + 1
        }
    }
    return 0
}

function setCookie(name, value, days) {
    days = 30
    if (days) {
        var date = new Date()
        date.setTime(date.getTime() + days * 24 * 60 * 60 * 1000)
        var expires = "; expires=" + date.toGMTString()
    } else var expires = ""
    document.cookie = name + "=" + value + expires + "; path=/"
}

function logout() {
    removeCookie("token")
    window.location.replace("./../../admin/sign-in")
}

function reloadScriptApp() {
    $("#reload_script_app").attr("src", "../views/admin/dist/js/app.js")
}

function reloadTagNumber() {
    $(".number").maskNumber({integer: true})
}

function formatMoney(nStr) {
    nStr += ""
    x = nStr.split(",")
    x1 = x[0]
    x2 = x.length > 1 ? "," + x[1] : ""
    var rgx = /(\d+)(\d{3})/
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, "$1" + "," + "$2")
    }
    return (x1 + x2).split(".")[0]
}

function tryParseInt(val) {
    try {
        val = val.toString()
        return parseInt(val.replace(",", "").replace("$", ""))
    } catch (e) {
        return 0
    }
}

function validateEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
    return re.test(String(email).toLowerCase())
}

function isDefine(val) {
    try {
        if (val == undefined) return false
        if (val == null) return false
        return true
    } catch (err) {
        return false
    }
}

function isNumber(val) {
    return !isNaN(val)
}

function showLoading() {
    // $("#DialogLoading").show()
    Swal.fire({
        title: 'Vui lòng đợi !',
        html: 'Loading...',// add html attribute if you want or remove
        allowOutsideClick: false,
    });
    Swal.showLoading()
}

function hideLoading() {
    Swal.hideLoading()
    swal.close()
    // $("#DialogLoading").hide()
}

function showPaging() {
    $("#SpnLoading").show()
}

function hidePaging() {
    $("#SpnLoading").hide()
}

function removeIndexOfArray(arr, index) {
    let arrTemp = []
    index = parseInt(index)
    for (let i = 0; i < arr.length && i < index; i++) {
        arrTemp.push(arr[i])
    }

    for (let i = index + 1; i < arr.length; i++) {
        arrTemp.push(arr[i])
    }

    return arrTemp
}

function copyToClipboard(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange()
        range.moveToElementText(document.getElementById(containerid))
        range.select().createTextRange()
        document.execCommand("copy")
    } else if (window.getSelection) {
        var range = document.createRange()
        range.selectNode(document.getElementById(containerid))
        window.getSelection().addRange(range)
        document.execCommand("copy")
    }
}

function empty(val) {
    // test results
    //---------------
    // []        true, empty array
    // {}        true, empty object
    // null      true
    // undefined true
    // ""        true, empty string
    // ''        true, empty string
    // 0         false, number
    // true      false, boolean
    // false     false, boolean
    // Date      false
    // function  false

    if (val === undefined) return true

    if (typeof val == "function" || typeof val == "number" || typeof val == "boolean" || Object.prototype.toString.call(val) === "[object Date]") return false

    if (val == null || val.length === 0)
        // null or 0 length array
        return true

    if (typeof val == "object") {
        // empty object

        var r = true

        for (var f in val) r = false

        return r
    }

    return false
}

function contains(input, val) {
    if (isDefine(input) && isDefine(val)) {
        for (let i = 0; i < val.length; i++) {
            if (!input.toUpperCase().includes(val[i].toUpperCase())) return false
        }
        return true
    } else {
        return false
    }
}

function CopyToClipboard(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange()
        range.moveToElementText(document.getElementById(containerid))
        range.select().createTextRange()
        document.execCommand("copy")
    } else if (window.getSelection) {
        var range = document.createRange()
        range.selectNode(document.getElementById(containerid))
        window.getSelection().addRange(range)
        document.execCommand("copy")
    }
}

function exportHTMLToWord(content) {
    var header = "<html xmlns:o='urn:schemas-microsoft-com:office:office' " + "xmlns:w='urn:schemas-microsoft-com:office:word' " + "xmlns='http://www.w3.org/TR/REC-html40'>" + "<head><meta charset='utf-8'><title>Export HTML to Word Document with JavaScript</title></head><body>"
    var footer = "</body></html>"
    var sourceHTML = header + content + footer

    var source = "data:application/vnd.ms-word;charset=utf-8," + encodeURIComponent(sourceHTML)
    var fileDownload = document.createElement("a")
    document.body.appendChild(fileDownload)
    fileDownload.href = source
    fileDownload.download = "document.doc"
    fileDownload.click()
    document.body.removeChild(fileDownload)
}

function swapDateMonth(date) {
    return date.split("/")[1] + "/" + date.split("/")[0] + "/" + date.split("/")[2]
}

// Phân trang
function paginator(total_count, curent_page) {
    $("#spn_loading").hide()

    let pagePatigation = ""
    pagePatigation += '<ul class="pagination home-product__pagination mt-4">'
    if (tryParseInt(limit) == 0) {
        limit = 20
    }
    page = tryParseInt(curent_page)
    const total_page = Math.ceil(tryParseInt(total_count) / tryParseInt(limit))
    if (total_page > 1) {
        if (page > 1) {
            //first page
            pagePatigation += `<li onclick="changeOffset(0)" class="paginate_button page-item first" id="paginationOptionDatatable_first" ><a href="javascript:void(0);" class="page-link"><i style="transform:rotate(180deg)" class="pagination-item__icon fas fa-angle-double-right"></i></a></li>`
            //previouse page
            pagePatigation += `<li onclick="previousPage()" class="paginate_button page-item previous"  ><a href="javascript:void(0);" class="page-link"><i class="pagination-item__icon fas fa-chevron-left"></i></a></li>`
        } else {
            if (page == 1) {
                //first page
                pagePatigation += `<li class="paginate_button page-item first disabled" id="paginationOptionDatatable_first"><a href="javascript:void(0);"  class="page-link"><i style="transform:rotate(180deg)" class="pagination-item__icon fas fa-angle-double-right"></i></a></li>`
            } else {
                //first page
                pagePatigation += `<li class="paginate_button page-item first " id="paginationOptionDatatable_first"><a href="javascript:void(0);"  class="page-link"><i style="transform:rotate(180deg)" class="pagination-item__icon fas fa-angle-double-right"></i></a></li>`
            }
        }
        //pages
        if (total_page <= 1 + isShow * 2) {
            for (let i = 0; i < total_page - 1; i++) {
                if (i == page - 1) {
                    pagePatigation += `<li onclick="changeOffset(${i})" class="paginate_button page-item active"><a href="javascript:void(0);"  class="page-link">${i + 1}</a></li>`
                } else {
                    pagePatigation += `<li onclick="changeOffset(${i})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">${i + 1}</a></li>`
                }
            }
        } else {
            //enough pages to hide some
            //close to beginning; only hide later pages
            if (page <= isShow) {
                for (let i = 0; i < isShow + 1; i++) {
                    if (i == page - 1) {
                        pagePatigation += `<li  onclick="changeOffset(${i})" class="paginate_button page-item active"><a href="javascript:void(0);"  class="page-link">${i + 1}</a></li>`
                    } else {
                        pagePatigation += `<li onclick="changeOffset(${i})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">${i + 1}</a></li>`
                    }
                }
                pagePatigation += `<li onclick="changeOffset(${Math.round((page + total_page) / 2)})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">...</a></li>`
            } else if (total_page - isShow > page && page > isShow) {
                //in middle; hide some front and some back
                //page 1
                pagePatigation += `<li onclick="changeOffset(${0})" class="paginate_button page-item"><a href="javascript:void(0);"  class="page-link">${1}</a></li>`
                //...
                pagePatigation += `<li onclick="changeOffset(${Math.round(page / 2)})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">...</a></li>`
                for (let i = page - 2; i < page + 1; i++) {
                    if (i == page - 1) {
                        pagePatigation += `<li onclick="changeOffset(${i})" class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">${i + 1}</a></li>`
                    } else {
                        pagePatigation += `<li onclick="changeOffset(${i})" class="paginate_button page-item"><a href="javascript:void(0);"  class="page-link">${i + 1}</a></li>`
                    }
                }
                //...
                pagePatigation += `<li onclick="changeOffset(${Math.round((page + total_page) / 2)})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">...</a></li>`
            } else {
                //close to end; only hide early pages
                //page 1
                pagePatigation += `<li onclick="changeOffset(${0})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">${1}</a></li>`
                //...
                pagePatigation += `<li onclick="changeOffset(${Math.round(page / 2)})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">...</a></li>`
                for (let i = total_page - isShow - 1; i < total_page - 1; i++) {
                    if (i == page - 1) {
                        pagePatigation += `<li onclick="changeOffset(${i})" class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">${i + 1}</a></li>`
                    } else {
                        pagePatigation += `<li onclick="changeOffset(${i})" class="paginate_button page-item"><a href="javascript:void(0);" class="page-link">${i + 1}</a></li>`
                    }
                }
            }
        }
        //next button
        if (page < total_page) {
            pagePatigation += `<li onclick="changeOffset(${total_page - 1})" class="paginate_button page-item"><a href="javascript:void(0);"  class="page-link">${total_page}</a></li>`
            pagePatigation += `<li onclick="nextPage()" class="paginate_button page-item next" id="paginationOptionDatatable_next"><a href="javascript:void(0);" class="page-link"><i class="pagination-item__icon fas fa-chevron-right"></i></a></li>`
            //last page
            pagePatigation += `<li onclick="changeOffset(${total_page - 1})" class="paginate_button page-item last" id="paginationOptionDatatable_last" ><a href="javascript:void(0);"  tabindex="10" class="page-link"><i class="pagination-item__icon fas fa-angle-double-right"></i></a></li>`
        } else {
            pagePatigation += `<li onclick="changeOffset(${total_page - 1})" class="paginate_button page-item active "><a href="javascript:void(0);"  class="page-link">${total_page}</a></li>`
            pagePatigation += `<li class="paginate_button page-item last disabled" id="paginationOptionDatatable_last" ><a href="javascript:void(0);" class="page-link"><i class="pagination-item__icon fas fa-angle-double-right"></i></a></li>`
        }
    } else {
        pagePatigation += `<li onclick="changeOffset(${0})" class="paginate_button page-item active"><a href="javascript:void(0);" class="page-link">${1}</a></li>`
    }
    pagePatigation += "</ul>"
    // $("#pagePatigation").addClass("mb-3");
    $("#pagePatigation").html(pagePatigation)
}

function nextPage() {
    page += 1
    let url = window.location.href.split("?")[0] + "?q=" + $("#input_keyword").val() + "&page=" + page + "&level=" + (urlParams.get("level") || 1)
    window.location.href = url
}

function previousPage() {
    page -= 1
    let url = window.location.href.split("?")[0] + "?q=" + $("#input_keyword").val() + "&page=" + page + "&level=" + (urlParams.get("level") || 1)

    if ($("#input_date_from").val()) {
        url += "&from=" + $("#input_date_from").val() + "&to=" + $("#input_date_to").val()
    }

    window.location.href = url
}

function changeOffset(page) {
    page += 1
    let url = window.location.href.split("?")[0] + "?q=" + $("#input_keyword").val() + "&page=" + page + "&level=" + (urlParams.get("level") || 1)

    if ($("#input_date_from").val()) {
        url += "&from=" + $("#input_date_from").val() + "&to=" + $("#input_date_to").val()
    }

    window.location.href = url
}

// This function keeps the casing unchanged for str, then perform the conversion
function toNonAccentVietnamese(str) {
    str = str.replace(/A|Á|À|Ã|Ạ|Â|Ấ|Ầ|Ẫ|Ậ|Ă|Ắ|Ằ|Ẵ|Ặ/g, "A");
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g, "a");
    str = str.replace(/E|É|È|Ẽ|Ẹ|Ê|Ế|Ề|Ễ|Ệ/, "E");
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g, "e");
    str = str.replace(/I|Í|Ì|Ĩ|Ị/g, "I");
    str = str.replace(/ì|í|ị|ỉ|ĩ/g, "i");
    str = str.replace(/O|Ó|Ò|Õ|Ọ|Ô|Ố|Ồ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ỡ|Ợ/g, "O");
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g, "o");
    str = str.replace(/U|Ú|Ù|Ũ|Ụ|Ư|Ứ|Ừ|Ữ|Ự/g, "U");
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g, "u");
    str = str.replace(/Y|Ý|Ỳ|Ỹ|Ỵ/g, "Y");
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g, "y");
    str = str.replace(/Đ/g, "D");
    str = str.replace(/đ/g, "d");
    // Some system encode vietnamese combining accent as individual utf-8 characters
    str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, ""); // Huyền sắc hỏi ngã nặng
    str = str.replace(/\u02C6|\u0306|\u031B/g, ""); // Â, Ê, Ă, Ơ, Ư

    return str;
}

function addressEnglish(str) {
    str = toNonAccentVietnamese(str)
    str = str.replace(/Huyen|Quan|huyen|quan/g, "")
    str = str.replace(/Xa|xa|Phuong|phiong/g, "")
    str = str.replace(/Tinh|tinh|Thanh Pho|thanh pho/g, "")

    values = str.split(', ')

    if (values.length >= 4) {
        str = values[0] + " Village, " + values[1] + " Ward, " + values[2] + " District, " + values[3] + " City"
    }

    return str
}

function nationalityEnglish(str) {
    str = toNonAccentVietnamese(str)

    if (str.includes('Trung Quoc') || str.includes('trung quoc')) {
        return "China"
    } else if (str.includes('Han Quoc') || str.includes('han quoc')) {
        return "Korea"
    } else if (str.includes('Nhat Ban') || str.includes('nhat ban')) {
        return "Japan"
    } else if (str.includes('Viet Nam') || str.includes('viet nam')) {
        return "Vietnamese"
    } else if (str.includes('Anh') || str.includes('anh')) {
        return "England"
    } else if (str.includes('Thai Lan') || str.includes('Thai lan')) {
        return "England"
    } else if (str.includes('an do') || str.includes('an do')) {
        return "India"
    } else if (str.includes('Dai Loan') || str.includes('dai loan')) {
        return "Taiwan"
    } else if (str.includes('Campuchia') || str.includes('campuchia')) {
        return "Cambodia"
    } else if (str.includes('Philippin') || str.includes('philippin') || str.includes('Philippines') || str.includes('philippines')) {
        return "Philippines"
    } else if (str.includes('Phap') || str.includes('phap')) {
        return "France"
    } else if (str.includes('My') || str.includes('my')) {
        return "American"
    } else if (str.includes('Nga') || str.includes('nga')) {
        return "Russian"
    } else if (str.includes('Uc') || str.includes('uc')) {
        return "Australian"
    } else if (str.includes('Lao') || str.includes('lao')) {
        return "Laos"
    }

    return str
}

function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function addUrlParameter(name, value) {
    const searchParams = new URLSearchParams(window.location.search)
    searchParams.set(name, value)
    window.location.search = searchParams.toString()
}

function addUrlParameterObjects($params) {
    const searchParams = new URLSearchParams(window.location.search)

    for (let i = 0; i < $params.length; i++) {
        if (!empty($params[i].name) && !empty($params[i].value)){
            searchParams.set($params[i].name, $params[i].value)
        }else{
            searchParams.delete($params[i].name)
        }
    }

    window.location.search = searchParams.toString()
}

$(document).ready(function () {
    $(document).on('click', '.action_delete', actionDelete);
    $("input").attr("autocomplete", "off");
});

