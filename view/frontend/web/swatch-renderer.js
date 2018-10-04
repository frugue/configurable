define([
	'df-lodash', 'jquery', 'mage/utils/wrapper'
], function(_, $, w) {'use strict';
return function(sb) {
var ids;
$.extend(sb.prototype, {
	_getSelectedAttributes: w.wrap(sb.prototype._getSelectedAttributes, function(_super) {
		var r = _super();
		var k = 'color_lg';
		// 2018-08-02
		// «Preselect the first color swatch for configurable products»:
		// https://github.com/frugue/configurable/issues/3
		// https://www.upwork.com/ab/f/contracts/20488254
		if (!$('body').hasClass('page-products') && !r[k]) {
			r[k] = $('.' + k + ' .swatch-option', this.element).first().attr('option-id');
		}
		// 2018-08-02
		// «Preselect the first color swatch for configurable products»:
		// https://github.com/frugue/configurable/issues/3
		// https://www.upwork.com/ab/f/contracts/20488254
		else {
			ids = ids || $('.filter-options .swatch-layered.color_lg .swatch-option.selected').map(function() {
				return $(this).attr('option-id');
			}).get();
			if (ids.length) {
				var $o = $('.swatch-option', this.element);
				var id = _.find(ids, function(id) {
					return $o.filter(function() {return id === $(this).attr('option-id');}).length;
				});
				if (id) {
					r[k] = id;
				}
			}
		}
		return r;
	})
});
return sb;};});