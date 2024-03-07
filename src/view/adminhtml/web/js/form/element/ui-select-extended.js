define([
    'Magento_Ui/js/form/element/ui-select',
    'underscore'
], function (uiSelect, _) {
    return uiSelect.extend({
        toggleOptionSelected: function (data, index, event) {
            if(event.target.className === 'group-items-action' && this.multiple) {
                let action = event.target.dataset.action;
                let activate = action === 'select-all';
                // Force activate/deactivate the target option
                if(activate) {
                    this.value.push(data.value);
                } else {
                    this.value(_.without(this.value(), data.value));
                }
                // Activate/deactivate the group options
                this._toggleGroupOptionSelected(data, activate);
                return this;
            }

            return this._super(data);
        },

        _toggleGroupOptionSelected: function (data, activate) {
            if(Array.isArray(data.optgroup) && data.optgroup.length) {
                for(let item of data.optgroup) {
                    if(activate) {
                        this.value.push(item.value);
                    } else {
                        this.value(_.without(this.value(), item.value));
                    }
                    if(item.hasOwnProperty('optgroup')) {
                        this._toggleGroupOptionSelected(item, activate);
                    }
                }
            }
        },
    })
})
