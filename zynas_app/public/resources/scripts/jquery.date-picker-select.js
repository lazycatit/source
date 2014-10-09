(function(jQuery){
    function FormSelect(baseId, config) {
        var d = new Date();
        var thisYear = d.getFullYear();
        var fields = {
            'year': {'unit': '年', 'top': thisYear - 100, 'bottom': thisYear + 0, 'interval': 1},
            'month': {'unit': '月', 'top': 1, 'bottom': 12, 'interval': 1},
            'day': {'unit': '日', 'top': 1, 'bottom': 31, 'interval': 1}
        };
        var index = 0;
        jQuery.each(fields, function(key, value) {
            fields[key]['id'] = baseId + '_' + key;
            fields[key]['index'] = index++;
        });
        jQuery.each(config, function(key, value) {if (key in fields) {fields[key] = jQuery.extend(fields[key], value);}});
        this.fields = fields;
        this.html = function(formSelect) {
            var html = [];
            jQuery.each(this.fields, function(key, value) {
                var unit = value['unit'];
                if (config.isDispTime || (key != 'hour' && key != 'minutes')) {
                    html.push('<select id="' + value['id'] + '" name="' + value['id'] + '">');
                    html.push('<option value="">--</option>');
                    for (var i = value['top']; i < value['bottom'] + 1; i = i + value['interval']) {
                        if (key == 'year') {html.push('<option value="' + i + '">' + i + '</option>');}
                        else {html.push('<option value="' + ("0" + i).slice(-2) + '">' + ("0" + i).slice(-2) + '</option>');}
                    }
                    html.push('</select>' + unit);
                }
            });
            return html.join("");
        }
        this.setValues = function(values) {
            jQuery.each(this.fields, function(key, value) {
                if (value['index'] < values.length) {
                    jQuery('#' + value['id']).val(values[value['index']]);
                }
            });
        }
        this.postData = function() {
            var delimiters = config['dateFormat'].match(/[- :\/]/g);
            if (!config.isDispTime) {
                delimiters = 'YY-MM-DD '.match(/[- \/]/g);
            }
            var data = [];
            jQuery.each(this.fields, function(key, value) {
                data.push(jQuery('#' + value['id']).val());
                if (jQuery.isArray(delimiters) && value['index'] < delimiters.length) {
                    data.push(delimiters[value['index']]);
                }
            });
            return (data.join("") == delimiters.join("")) ? '' : data.join("");
        }
    }

    function defautlValues(value, format) {
        switch (format) {
            case 'YY-MM-DD': return value.split(/[- :\/]/);
            default: return false;
        }
    }

    jQuery.fn.datePickerSelect = function(config){
        var defaults = {
            'dateFormat': 'YY-MM-DD',
            'clearImage': '/images/clear.png',
            'buttonImage': '/images/calendar.png',
            'isDispTime': true
        };
        var options = jQuery.extend(defaults, config);
        return this.each(function(i){
            var _this = jQuery(this);
            var id = jQuery(this).attr('name');
            var formSelect = new FormSelect(id, options);
            var values = defautlValues(jQuery(this).val(), options['dateFormat']);
            var idClear = id + '_clear';
            _this.before(formSelect.html());
            _this.after('<img src="' + options['clearImage'] + '" id="' + idClear + '" />');
            if (jQuery.isArray(values)) {formSelect.setValues(values);}
            jQuery.each(formSelect.fields, function(key, value) {
                jQuery('#' + value['id']).change(function(ev) {
                    _this.val(formSelect.postData());
                });
            });
            jQuery('#' + idClear).click(function(ev) {
                formSelect.setValues(['', '', '', '', '']);
                _this.val('');
            });
            jQuery(this).datepicker({
                showOn: 'button',
                buttonImage: options['buttonImage'],
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                onClose: function(dateText, inst) {
                    var values = dateText.split(/[- :\/]/);
                    if (values != '') {
                        formSelect.setValues(values);
                    }
                    _this.val(formSelect.postData());
                }
            });
        });
    };
})(jQuery);