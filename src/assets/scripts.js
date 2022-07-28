/** 
 * Adds the scripts for running the benchmark
 */
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.miw-pb-form');
  const table = document.querySelector('.wp-list-table.benchmarks tbody');

  if( ! form ) {
    return;
  }

  // Reset all benchmarks
  const reset = document.querySelector('.miw-pb-table [name="reset"]');

  reset.addEventListener('click', async (event) => {
    event.preventDefault();

    if( ! confirm('Do really want to clear all tests?') ) {
      return;
    }

    // Remove the items in the database
    const body = new FormData();
    body.append('nonce', MIWPB.nonce);  
    body.append('action', 'miw_pb_delete_results');    

    await fetch(MIWPB.ajaxUrl, {
      method: "POST",
      credentials: "same-origin",
      body,
    });

    // Remove the rows
    const tableRows = table.querySelectorAll('tr');
    if( ! tableRows || tableRows.length < 1 ) {
      return;
    }
    for( row of tableRows ) {
      row.remove();
    }

    // Readd the empty message
    table.insertAdjacentHTML('afterbegin', `<tr class="no-items"><td class="colspanchange" colspan="5">No benchmark results.</td></tr>`);


  });

  // Running the actions. Not really dry, but good enough - refactoring can always be done alter
  form.addEventListener('submit', async (event) => {
    event.preventDefault();

    // Dom Elements
    const infoNotification = form.querySelector('.notice-info');
    const successNotication = form.querySelector('.notice-success');
    const errorNotification = form.querySelector('.notice-error');

    // Value containers
    const number = form.querySelector('#miw_pb_number').value;
    let insertTime;
    let queryTime;
    let deleteTime;

    // Reset notifications
    successNotication.classList.add('hidden');
    errorNotification.classList.add('hidden');
    infoNotification.classList.remove('hidden');

    // Our FormData
    const body = new FormData();
    body.append('number', number);
    body.append('nonce', MIWPB.nonce);    

    // Inserting
    try {
      body.append('action', 'miw_pb_insert');
      const insertRequest = await fetch(MIWPB.ajaxUrl, {
        method: "POST",
        credentials: "same-origin",
        body,
      });
      insertTime = await insertRequest.json();
    } catch {
      infoNotification.classList.add('hidden');
      errorNotification.classList.remove('hidden');
    } 

    // Querying
    try {
      body.set('action', 'miw_pb_query');
      const queryRequest = await fetch(MIWPB.ajaxUrl, {
        method: "POST",
        credentials: "same-origin",
        body,
      });
      queryTime = await queryRequest.json();
    } catch {
      infoNotification.classList.add('hidden');
      errorNotification.classList.remove('hidden');
    }
    
    // Deleting
    try {
      body.set('action', 'miw_pb_delete');
      const deleteRequest = await fetch(MIWPB.ajaxUrl, {
        method: "POST",
        credentials: "same-origin",
        body,
      });
      deleteTime = await deleteRequest.json();
    } catch {
      infoNotification.classList.add('hidden');
      errorNotification.classList.remove('hidden');
    }   
    
    // Adding the results
    body.set('action', 'miw_pb_update_results');
    body.append('insert_time', insertTime);
    body.append('query_time', queryTime);
    body.append('delete_time', deleteTime);

    const updated = await fetch(MIWPB.ajaxUrl, {
      method: "POST",
      credentials: "same-origin",
      body,
    });   

    const response = await updated.json();
    infoNotification.classList.add('hidden');

    if( ! response.success ) {
      infoNotification.classList.add('hidden');
      errorNotification.classList.remove('hidden'); 
      return;
    }

    // Add the result to the table dynamically
    const rowNoItems = table.querySelector('tr.no-items');

    if( rowNoItems ) {
      rowNoItems.remove();
    }

    let color;

		if( response.data.score >= 8 ) {
			color = '#1ed14b';
		} else if( response.data.score >= 6 ) {
			color = '#f0c33c';
		} else if( response.data.score >= 4 ) {
			color = '#bd8600';
		}	else if( response.data.score >= 2 ) {
			color = '#e65054';
		}	else if( response.data.score >= 0 ) {
			color = '#d63638';
		}	    

    const resultRowHTMLString = `<tr>
      <td class="id column-id has-row-actions column-primary" data-colname="ID">#${response.data.id}</td>
      <td class="number column-number" data-colname="Number of Posts"><strong>${number}</strong></td>
      <td class="insert_time column-insert_time" data-colname="Insert Time">${insertTime} <i>seconds</i></td>
      <td class="query_time column-query_time" data-colname="Query Time">${queryTime} <i>seconds</i></td>
      <td class="delete_time column-delete_time" data-colname="Delete Time">${deleteTime} <i>seconds</i></td>
      <td class="score column-score" data-colname="Score"><b style="background-color: ${color}; border-radius: 5px; color: #fff; padding: 0.5em; ">${response.data.score}</b></td>
    </tr>`;
    table.insertAdjacentHTML('afterbegin', resultRowHTMLString);

    successNotication.classList.remove('hidden');

  });
});