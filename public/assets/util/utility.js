
/**
 * CRUD Operation Type Constants Object
 */
const OPERATION_TYPES = {
    CREATE: 'create',
    READ: 'read',
    UPDATE: 'update',
    DELETE: 'delete'
}

/**
 * Laravel Ajax Request Methods
 */
const AJAX_REQUEST_METHODS = {
    POST: 'POST',
    GET: 'GET',
    PUT: 'PUT',
    PATCH: 'PATCH',
    DELETE: 'DELETE'
}

/**
 * Sets up an ajax object with option like the 'header' etc...
 * It's adviced to always call this function at the begining
 * of your JQuery script.
 * @param {Object} setupOptions Options to initialize ajaxSetup with
 */
const initAjaxSetup = (setupOptions) => {
    $.ajaxSetup(setupOptions);
}


/**
 * Utility http post for server request with JSON object data.
 * @param {URL} url Url of the server API to process the rquest.
 * @param {JSON} jsonData JSON object sent to the server API as a parameter to process alongside the request.
 * @param {Function} successCallback A callback function that executes after the request succeeds.
 * @param {Function} errorCallback A callback function that executes after the request fails.
 * @param {Boolean} async Tells ajax to request asynchronously.
 */
const AJAX_OPERATION = (url, method, jsonData, successCallback, errorCallback) => {

    var options = {
        beforeSend: function (xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
        url: url,
        type: method,
        data: {
            data: jsonData
        },
        success: successCallback,
        error: errorCallback
    };
    return $.ajax(options);

}

const AJAX_OP2 = (url, method, jsonData, successCallback, errorCallback) => {

    var options = {
        beforeSend: function (xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
        url: url,
        type: method,
        data: jsonData,
        contentType: false,
        processData: false,
        success: successCallback,
        error: errorCallback
    };
    return $.ajax(options);

}

/**
 * Validates date fields `day, month` and `year` through
 * thier selected values. It returns true only and only if
 * their selcted value are not either of `dd`, `mm` or `yy`
 * @param {JQuery<HTMLElement>} dateElementObject
 */
const validateDateBySelectedValue = (dateElementObject) => {

    const day = dateElementObject.day;
    const month = dateElementObject.month;
    const year = dateElementObject.year;

    return (day !== 'dd' && month !== 'mm' && year !== 'yy');
}

/**
 * Validates date fields `day, month` and `year` through
 * thier selected indices. It returns true only and only if
 * their selected indices are not zero(s).
 * @param {JQuery<HTMLElement>} dateElementObject
 */
const validateDateBySelectedIndex = (dateElementObject) => {

    const dayIndex = dateElementObject.day.prop('selectedIndex');
    const monthIndex = dateElementObject.month.prop('selectedIndex');
    const yearIndex = dateElementObject.year.prop('selectedIndex');

    return (dayIndex > 0 && monthIndex > 0 && yearIndex > 0);
}

/**
 * Validates select elements. Returns true only if the
 * selected index in not 0 (zero).
 * @param {JQuery<HTMLElement>} selectElement
 */
const validateSelectByIndex = (selectElement) => {

    const selectedIndex = selectElement.prop('selectedIndex');

    return selectedIndex > 0;
}

/**
 * Validates email input. It ensures it matches email standard format
 * @param {String} email
 */
const isEmailValid = (email) => {

    var emailRegexPatt = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    return emailRegexPatt.test(email);

}


/**
 * Resets a form's elements
 */
const resetAllFormElements = (form, element) => {

    /**
     * Returns the value of the elements to defualt
     */
    if (element.is('select')) {
        element.find('option:first').prop('selected', true);
    } else {
        element.val('');
    }

    if (element.hasClass('valid')) {
        element.removeClass('valid');
    }

    /**
     * Clears any data found on the table within the form
     * if a table or more exist and eventaully have some rows
     */
    if (form.find('table').length > 0) {

        const tables = form.find('table');
        const tbodys = tables.find('tbody');

        if (tbodys.length > 0) {
            tbodys.children().remove();
        }

    }

    //OTHER ELEMENTS (E.g Checkbox, Radio Buttons) VALIDATION COULD COME IN...
}

/**Initializes All Material js Components */
const initMaterializeComponents = () => {
    /**
     * Initializes all component
     */
    M.AutoInit();

    /**
     * Enables dropdown
     */
    $(".dropdown-trigger").dropdown();

    /**
     * Enables modal launch
     */
    $('.modal').modal({ dismissible: false });

    /**
     * Enables Side Nav
     */
    $('.sidenav').sidenav();

    /**
     * Enables material Box
     */
    $('.materialboxed').materialbox();

    $('select').formSelect();

    let tips = document.querySelectorAll('.tooltipped');
    M.Tooltip.init(tips);
}

$(document).ready(function () {
    $('.sidenav').sidenav();
});

export default AJAX_OPERATION;
export {
    validateDateBySelectedValue,
    validateDateBySelectedIndex,
    isEmailValid,
    resetAllFormElements,
    initMaterializeComponents,
    OPERATION_TYPES,
    AJAX_REQUEST_METHODS,
    initAjaxSetup,
    validateSelectByIndex,
    AJAX_OP2
};
