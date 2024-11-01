// Element.closest() polyfill
if ( ! Element.prototype.closest ) {
	if ( ! Element.prototype.matches ) {
		// @ts-ignore
		Element.prototype.matches = Element.prototype.msMatchesSelector || Element.prototype.webkitMatchesSelector;
	}
	Element.prototype.closest = function( s: any ) {
		const el = this;
		let ancestor = this;
		if ( ! document.documentElement.contains( el ) ) {
			return null;
		}
		do {
			if ( ancestor.matches( s ) ) {
				return ancestor;
			}
			// @ts-ignore
			ancestor = ancestor.parentElement;
		} while ( ancestor !== null );
		return null;
	};
}
