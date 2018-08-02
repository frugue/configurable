// 2018-08-02
// «Preselect the first color swatch for configurable products»
// https://github.com/frugue/configurable/issues/3
// https://www.upwork.com/ab/f/contracts/20488254
define([
	'df-lodash', 'jquery', 'mage/utils/wrapper'
], function(_, $, w) {'use strict'; return function(sb) {
$.extend(sb.prototype, {
	_getSelectedAttributes: w.wrap(sb.prototype._getSelectedAttributes, function(_super) {
		var r = _super();
		var k = 'color_lg';
		if (!r[k]) {
			r[k] = $('.' + k + ' .swatch-option', this.element).first().attr('option-id');
		}
		return r;
	})
});
return sb;};});