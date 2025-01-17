<div class="content-wrapper">
    <section class="content-header">
		<h1>
			List of Item Request Form
		</h1>
	</section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-body">
                        <div id="tabs">
                            <ul id="tabs2" style="max-width: 100%; margin: 0 auto;">
                            <!-- Tab headers will be injected here -->    
                            </ul>
                            <!-- Tab content will be injected here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<script>
     $(document).ready(function() {
        $.ajax({
            url: base_url + 'AdminTraccReq_controller/item_req_form_JTabs/' + <?= $user_details['dept_id']; ?>,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.message === 'success') {
                    console.log("User Role:", response.user_role);
                    const userRole = response.user_role; 
                    let groupedTickets = {};  // To group tickets by recid
                    let counter = 1;  
                    let tabsHtml = ''; 
                    let contentHtml = '';

                    // Group tickets by recid
                    $.each(response.data, function(index, ticket) {
                        const recid = ticket.recid;
                        
                        // If this recid doesn't exist in the groupedTickets object, create an entry
                        if (!groupedTickets[recid]) {
                            groupedTickets[recid] = [];
                        }
                        
                        // Push the ticket into the correct group
                        groupedTickets[recid].push(ticket);
                    });

                    // Now generate the HTML for each tab based on recid
                    $.each(groupedTickets, function(recid, tickets) {
                        const tabId = "tabs-" + recid; // Use recid for unique tab id

                         // Create tab entry with ticket_id and recid in the tab title
                        $.each(tickets, function(index, ticket) {
                            tabsHtml += `
                            <li data-recid="${ticket.recid}">
                                <a href="#tabs-${ticket.recid}">${ticket.ticket_id} (${ticket.recid})</a>
                                ${
                                    userRole !== "L2"
                                        ? `<span class="ui-icon ui-icon-close close-tab-btn" role="presentation" title="Close Tab"></span>`
                                        : ""
                                }
                            </li>`;
                        });

                        // Create content for this tab, which is a group of tickets with the same recid
                        let tabContent = '';
                        $.each(tickets, function(index, ticket) {
                            tabContent += `
                                <div class="ticket-details">
                                    <div style="display: flex; gap: 50px; align-items: center;">
                                        <h3>Ticket ID: ${ticket.ticket_id}</h3>
                                        <h3>Record ID: ${ticket.recid}</h3>
                                    </div>
                                    <p>${ticket.form_html}</p>
                                </div>
                            `;
                        });

                        // Add the generated tab content to the contentHtml
                        contentHtml += `<div id="${tabId}">${tabContent}</div>`;
                    });

                    // Append tabs and content to the DOM
                    $('#tabs ul').html(tabsHtml);
                    $('#tabs').append(contentHtml);
                    
                    // Initialize jQuery UI Tabs
                    $('#tabs').tabs();
                } else {
                    // alert('Failed to load tickets.');
                }
            }
        });

        $('#tabs').on('click', '.close-tab-btn', function () {
        const $tab = $(this).closest('li'); // Get the tab element
        const recid = $tab.data('recid'); // Fetch the unique recid
        const lmiItemCode = $(`#tabs-${recid}`).find('#lmi_item_code').val(); // Get the value of lmi_item_code for this tab

            Swal.fire({
                title: 'Are you Done?',
                text: "You won't be able to undo this action!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, I\'m done!',
                cancelButtonText: 'Cancel',
                width: '500px', 
                padding: '2rem', 
                customClass: {
                    popup: 'large-popup', 
                    title: 'large-popup-title', 
                    content: 'large-popup-content', 
                    confirmButton: 'large-popup-button', 
                    cancelButton: 'large-popup-button'
            }
            }).then((result) => {
                if (result.isConfirmed) {
                    const panelId = $tab.remove().attr('aria-controls'); // Remove the tab
                    $(`#${panelId}`).remove(); // Remove the corresponding content
                    $('#tabs').tabs('refresh'); // Refresh the tabs widget

                    $.ajax({
                        url: base_url + 'AdminTraccReq_controller/update_irf_ticket_remarks',
                        type: 'POST',
                        data: {
                            recid: recid,
                            lmi_item_code: lmiItemCode,
                        },
                        success: function (response) {
                            const res = JSON.parse(response);
                            if (res.message === 'success') {
                                Swal.fire({
                                    title: 'Done!',
                                    text: `Form with recid ${recid} has been marked as "Done".`,
                                    icon: 'success',
                                    width: '500px', 
                                    padding: '2.5rem', 
                                    customClass: {
                                        title: 'large-done-title',    
                                        confirmButton: 'large-button' 
                                    }
                                });
                                console.log(`Form with recid ${recid} marked as "Done".`);
                            } else {
                                Swal.fire('Error', `Failed to update form: ${res.error}`, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'An error occurred while updating the form.', 'error');
                        }
                    });
                }
            });
        });
    });

    // $('#tabs').on('click', '.close-tab-btn', function () {
    //     const $tab = $(this).closest('li'); // Get the tab element
    //     const recid = $tab.data('recid'); 
    //     const panelId = $tab.remove().attr('aria-controls'); // Remove the tab
    //     $(`#${panelId}`).remove(); // Remove the corresponding content
    //     $('#tabs').tabs('refresh'); // Refresh the tabs widget

    //     $.ajax({
    //         url: base_url + 'main/update_irf_ticket_remarks', 
    //         type: 'POST',
    //         data: { recid: recid }, // Pass the unique recid
    //         success: function (response) {
    //             const res = JSON.parse(response);
    //             if (res.message === 'success') {
    //                 console.log(`Form with recid ${recid} marked as "Done".`);
    //             } else {
    //                 alert(`Failed to update form with recid ${recid}: ${res.error}`);
    //             }
    //         },
    //         error: function () {
    //             alert(`An error occurred while updating form with recid ${recid}.`);
    //         }
    //     });
    // });

    
</script>