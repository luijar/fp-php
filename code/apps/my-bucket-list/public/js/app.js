

const items = document.querySelectorAll('input[type="checkbox"]');

Rx.Observable.fromEvent(items, 'change')
    .bufferTime(4000)
    .switchMap(changedItems => Rx.Observable.from(changedItems))
    .map(event => ({id: event.target.id, status: event.target.checked}))
    .switchMap(obj => Rx.Observable.ajax{url:`/complete/${obj.id}`, method: 'POST'})    
	.subscribe(
		({id, status}) => {
			if(status) {				
				let elem = document.querySelector(`#delete-${id}`);
				elem.style.display = 'inline';
				elem.parentElement.className = 'done';					
			}
		}	
	);