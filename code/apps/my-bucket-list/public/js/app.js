const items = document.querySelectorAll('input[type="checkbox"]');

Rx.Observable.fromEvent(items, 'change')
    .bufferTime(4000)
    .switchMap(changedItems => Rx.Observable.from(changedItems))
    .map(event => ({id: extractId(event.target.id), status: event.target.checked}))
    .do(event => console.log(`Sending complete request for ${event.id}`))
    .mergeMap(obj => Rx.Observable.ajax({url:`/api/complete/${obj.id}`, method: 'POST'}))        
    .map(data => data.response)    
	.subscribe(
		({id, status}) => {
			if(status) {								
				let elem = document.querySelector(`#delete-item-${id}`);
				elem.style.display = 'inline';
				elem.parentElement.className = 'done';					
				elem = document.querySelector(`#item-${id}`).disabled = true;
			}
		}	
	);

Rx.Observable.timer(4000)
	.take(1)
	.subscribe(() => {
		let elem = document.querySelector('#info-panel');		
		if(elem) {
			elem.style.display = 'none';	
		}
	});


// Helper functions
const extractId = str => str.match(/item-([\d]+)/)[1];