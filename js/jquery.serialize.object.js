$.fn.serializeObject = function (allowEmptyValues) {
	var o = {};
	var a = this.serializeArray();
	$.each(a, function () {
		if (allowEmptyValues || (this.value !== undefined && this.value !== '')) {
			if (o[this.name] !== undefined) {
				if (!o[this.name].push) {
					o[this.name] = [o[this.name]];
				}
				o[this.name].push(this.value);
			} else {
				o[this.name] = this.value;
			}
		}
	});
	return o;
};