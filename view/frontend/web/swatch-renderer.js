define([
	'df-lodash', 'jquery', 'mage/utils/wrapper'
], function(_, $, w) {'use strict';
return function(sb) {
var ids;
var isProductsList = $('body').hasClass('page-products');

$.extend(sb.prototype, {
	_getSelectedAttributes: w.wrap(sb.prototype._getSelectedAttributes, function(_super) {
		var r = _super();
		var k = 'color_lg';
		// 2018-08-02
		// «Preselect the first color swatch for configurable products»:
		// https://github.com/frugue/configurable/issues/3
		// https://www.upwork.com/ab/f/contracts/20488254
		if (!isProductsList && !r[k]) {
			r[k] = $('.' + k + ' .swatch-option', this.element).first().attr('option-id');
		}
		// 2018-10-05
		// «The frontend product list should show product images according to the chosen color»:
		// https://github.com/frugue/core/issues/26
		// https://www.upwork.com/ab/f/contracts/20616130
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
	,_RenderControls: w.wrap(sb.prototype._RenderControls, function(_super) {
		if (!isProductsList) {
			_super();
		}
		else {
			var wait = function() {
				$('.filter-options .swatch-layered.color_lg').length
					? _super() : setTimeout(function() {wait();}, 100)
				;
			};
			wait();
		}
	})
});
return sb;};});