
document.addEventListener( 'click', handlePrivatePostClicked );
function handlePrivatePostClicked( e: MouseEvent ) {
	if ( ! e.target || ! ( e.target instanceof Element ) ) {
		return;
	}
	const target: Element = e.target;

	if ( target.nodeName !== 'A' ) {
		return;
	}

	if ( '#' !== target.getAttribute( 'href' ) ) {
		return;
	}

	if ( null === target.closest( '.twrp-block' ) ) {
		return;
	}

	e.preventDefault();
}
