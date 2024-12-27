<style>

    #report_box {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        width: 100%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 10px;
    }

    #report_filter_box {
        width: 440px;
        padding: 20px;
    }

    .report_filter_form {
        height: 300px;
        width: 400px;
    }

    .report_label {
        font-size: 18px;
    }

    .report_input {
        border-width: 0;
        padding: 7px;
        border-radius: 15px;
        width: 100%;
    }

    .report_button {
        padding: 7px;
        width: 100%;
        border-radius: 15px;
        border-width: 0;
        background-color: lightgreen;
    }

#tblMsrfBox,
#tblTraccBox,
#tblTraccRequest {
    width: 440px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.dt-buttons {
    width: 440px;
    display: flex;
    flex-direction: column;
}

.btn-export {
    margin: 5px;
    padding: 5px;
    border-radius: 15px;
    background-color: #e04c3c;
    border: none;
    color: white;
}

</style>

<div class="content-wrapper">
    <section class="content-header">
        </section>
        <section class="content">
            <div class="row">
                <div class="col-xs-12" id="report_box">
                    <h1>Generate Reports</h1>
                    <div class="box" id="report_filter_box">

                    <table class="report_filter_form">
                        <tr>
                            <!-- Start Date Picker -->
                            <td><label for="start_date" class="report_label">Start Date:</label></td>
                            <td><input type="date" id="start_date" name="start_date" class="report_input"></td>
                        </tr>
                        <tr>
                            <!-- End Date Picker -->
                            <td><label for="end_date" class="report_label">End Date:</label></td>
                            <td><input type="date" id="end_date" name="end_date" class="report_input">
                        </tr>
                        <tr>
                            <!-- Status Picker -->
                            <td><label for="status" class="report_label">Status:</label></td>
                            <td>
                                <select id="status" class="report_input">
                                    <option value="">All</option>
                                    <option value="Open">Open</option>
                                    <option value="In Progress">In Progress</option>
                                    <option value="On Going">On Going</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Closed">Closed</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <!-- Choose between MSRF or Tracc -->
                            <td><label for="ticket" class="report_label">Ticket:</label></td>
                            <td>
                                <select id="ticket" class="report_input">
                                    <option value="msrf">MSRF Ticket</option>
                                    <option value="tracc">Tracc Concern</option>
                                    <option value="request">Tracc Request</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan=2><button id="filter" class="report_button">Filter</button></td>
                        </tr>
                    </table>
                    
                </div>

                <div class="box-body" id="tblMsrfBox" style="display: none">
                    <table id="tblTicketsPrint" class="table table-bordered table-striped" style="display: none">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Requestor Name</th>
                                <th>Department</th>
                                <th>Date Requested</th>
                                <th>Date Needed</th>
                                <th>Asset Code</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="box-body" id="tblTraccBox" style="display: none">
                    <table id="tblTicketsTraccConcernPrint" class="table table-bordered table-striped" style="display: none">
                        <thead>
                            <tr>
                                <th>Control Number</th>
                                <th>Reported By</th>
                                <th>Reported Date</th>
                                <th>Resolved Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>

                <div class="box-body" id="tblTraccRequest" style="display: none">
                    <table id="tblTicketsTraccRequestPrint" class="table table-bordered table-striped" style="display: none">
                        <thead>
                            <tr>
                                <th>Ticket ID</th>
                                <th>Requested By</th>
                                <th>Department</th>
                                <th>Date Requested</th>
                                <th>Company</th>
                                <th>Complete Details</th>
                                <th>Accomplished By</th>
                                <th>Accomplished Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        // Kevin's codes: The tables would be hidden and users would choose which table reports to generate.
        $('#tblMsrfBox').hide();
        $('#tblTraccBox').hide();
        $('#tblTraccRequest').hide();
        $('#usersTable').hide();

        // Kevin's codes: format date from yyyy-mm-dd to mm-dd-yyyy
        function formatDate(date) {
            if(date == '0000-00-00') {
                return '';
            } else {
                var d = new Date(date);
                var month = d.toLocaleString('en-US', { month: 'short' });
                var day = d.getDate();
                var year = d.getFullYear();

                return month + " " + day + " " + year;
            }
        }

        // Upon clicking of the #filter button, the program will get the start date, end date, the status, and the ticket type to filter the results based on these dates and would populate the table based on the results.
        $('#filter').click(function() {
            // importing the jsPDF function.
            const {jsPDF} = window.jspdf;

            // Get value from the element with an id of 'start_date', 'end_date', 'status', and 'ticket'.
            var startDate = $('#start_date').val();
            var endDate = $('#end_date').val();
            var status = $('#status').val();
            var ticket = $('#ticket').val();

            // Instantiate a new Date variable.
            var date = new Date();

            // Variable to store and display start date to end date.
            var dateRange = '';

            // Hides and shows table based on user request.
            if(ticket == 'msrf') {
                $('#tblMsrfBox').show();
                $('#tblTraccBox').hide();
                $('#tblTraccRequest').hide();
            } else if (ticket == 'tracc') {
                $('#tblMsrfBox').hide();
                $('#tblTraccBox').show();
                $('#tblTraccRequest').hide();
            } else if (ticket == 'request') {
                $('#tblMsrfBox').hide();
                $('#tblTraccBox').hide();
                $('#tblTraccRequest').show();
            }

            // Checks if start and end dates variable has value.
            if(startDate == '' && endDate == '') {
                dateRange = 'All reports';
            } else if (endDate == '') {
                dateRange = 'All reports from ' + formatDate(startDate);
            } else if (startDate == '') {
                dateRange = 'All reports to ' + formatDate(endDate);
            } else {
                dateRange = formatDate(startDate) + ' to ' + formatDate(endDate);
            }

            // Table MSRF Concern creation
            // "destroy" - allows reinstantiation of the table
            // "searching" - enables/disables search functionality
            // "paging" - enables/disables limiting of the results shown on the screen.
            var tableMSRF = $('#tblTicketsPrint').DataTable({
                "info": false,
                "destroy": true,
                "searching": false,
                "paging": false,
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/print_tickets_msrf",
                    "type": "POST",
                    "data": {
                        start_date: startDate,
                        end_date: endDate,
                        status: status,
                    },
                },
                // Since we are not outputing all the columns, we need to define the columns.
                "columns": [
                    { "data": "ticket_id" },
                    { "data": "requestor_name" },
                    { "data": "department" },
                    { "data": "date_requested" },
                    { "data": "date_needed" },
                    { "data": "asset_code" },
                    { "data": "status" }
                ],
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [
                    {
                        // Export table to a PDF document.
                        text: 'PDF',
                        className: 'btn-export',
                        action: function () {
                            // Instantiate jsPDF.
                            var doc = new jsPDF();

                            // Set page number
                            var page = 1;

                            // Place all data into a variable.
                            var tableContent = $('#tblTicketsPrint')[0];

                            // Styling the table.
                            doc.autoTable({
                                html: tableContent,
                                theme: 'grid',
                                headStyles: {
                                    fillColor: 'white',
                                    textColor: 'black',
                                    fontStyle: 'bold',
                                    fontSize: 10,
                                    lineWidth: 0.5,
                                },
                                bodyStyles: {
                                    fillColor: 'white',
                                    textColor: 'black',
                                    fontSize: 10,
                                    lineWidth: 0.5,
                                },
                                styles: {
                                    cellPadding: 2,
                                    fontSize: 12,
                                    bordered: true,
                                },
                                margin: {
                                    top: 30
                                },
                                didDrawPage: function(data) {
                                    // This section will appear to all pages.

                                    // Insert Logo
                                    var imageUrl = '<?= base_url('assets/images/lifestrong-logo.png'); ?>';
                                    doc.addImage(imageUrl, "PNG", 15, 2, 10, 10);
                                    
                                    // Set font size and text to the title of the document.
                                    doc.setFontSize(20);
                                    doc.text('Generated Report For MSRF Concern', 28, 10);

                                    // Set font size and text to display the filtered start and end date of the report.
                                    doc.setFontSize(12);
                                    doc.text('Date range: ' + dateRange, 15, 20);
                                    doc.text('Date printed: ' + formatDate(date), 15, 26);

                                    // Set font size and position of the page number.
                                    doc.setFontSize(10);
                                    doc.text(page.toString(), 200, 290);

                                    // Increment the page number per page.
                                    page++;
                                }
                            });
                            
                            // Open the document to another window.
                            doc.output('dataurlnewwindow', 'MSRF Concern ' + '(' + dateRange + ')');
                            doc.save('MSRF Concern ' + '(' + dateRange + ')');
                        }
                    },
                    {
                        // Export table to an Excel document.
                        text: 'Excel',
                        className: 'btn-export',
                        extend: 'excel',
                        filename: '(' + formatDate(date) + ') MSRF Concern (' + dateRange + ')',
                        title: 'Open MSRF Tickets (' + dateRange + ')',
                    },
                    {
                        // Export table to a CSV document.
                        text: 'CSV',
                        className: 'btn-export',
                        extend: 'csv',
                        filename: '(' + formatDate(date) + ') MSRF Concern (' + dateRange + ')',
                        customize: function(csv) {
                            var rows = csv.split('\n');

                            var title = 'Open MSRF Tickets (' + dateRange + ')';
                            rows.unshift(title);

                            return rows.join('\n');
                        }
                    }
                ]
            });

            // Table Tracc Concern creation
            // "destroy" - enables the reinstantiation of the table.
            // "searching" - enables/disables searching functionality.
            // "paging" - enables/disables limiting of the data displayed in the table.
            var tableTracc = $('#tblTicketsTraccConcernPrint').DataTable({
                "info": false,
                "destroy": true,
                "searching": false,
                "paging": false,
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/print_tickets_tracc_concern",
                    "type": "POST",
                    "data": {
                        start_date: startDate,
                        end_date: endDate,
                        status: status
                    },
                },
                // Since we are not outputing all the columns, we need to define the columns.
                "columns": [
                    { "data": "control_number" },
                    { "data": "reported_by" },
                    { "data": "reported_date" },
                    { "data": "resolved_date" },
                    { "data": "status" },
                ],
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [
                    {
                        // Export the table to a PDF document.
                        text: 'PDF',
                        className: 'btn-export',
                        action: function () {

                            // Instantiate the jsPDF class.
                            var doc = new jsPDF();

                            // Set page number
                            var page = 1;

                            // Get the data from table.
                            var tableContent = $('#tblTicketsTraccConcernPrint')[0];

                            // Styling of table.
                            doc.autoTable({
                                html: tableContent,
                                theme: 'grid',
                                headStyles: {
                                    fillColor: 'white',
                                    textColor: 'black',
                                    fontSize: 10,
                                    fontStyle: 'bold',
                                    lineWidth: 0.5,
                                },
                                bodyStyles: {
                                    fillColor: 'white',
                                    textColor: 'black',
                                    fontSize: 10,
                                    lineWidth: 0.5,
                                },
                                styles: {
                                    cellPadding: 4,
                                    fontSize: 12,
                                    bordered: true,
                                },
                                margin: {
                                    top: 30
                                },
                                didDrawPage: function (data) {
                                    // Insert Logo
                                    var imageUrl = '<?= base_url('assets/images/lifestrong-logo.png'); ?>';
                                    doc.addImage(imageUrl, "PNG", 15, 2, 10, 10);
                                    
                                    // Set the font size and the text to the title of the document.
                                    doc.setFontSize(20);
                                    doc.text('Generated Report For Tracc Concern', 28, 10);
                                    
                                    // Set the font size and the text to the filtered start and end date of the report.
                                    doc.setFontSize(12);
                                    doc.text(dateRange, 15, 20);
                                    doc.text('Date printed: ' + formatDate(date), 15, 26);

                                    // Set font size and position of the page number.
                                    doc.setFontSize(10);
                                    doc.text(page.toString(), 200, 290);

                                    // Increment the page number per page.
                                    page++;
                                }
                            });
                            
                            doc.output('dataurlnewwindow', 'Tracc Concern ' + '(' + dateRange + ')');
                            doc.save('Tracc Concern ' + '(' + dateRange + ')');
                        }
                    },
                    {
                        // Export the table to an Excel document.
                        text: 'Excel',
                        className: 'btn-export',
                        extend: 'excel',
                        filename: '(' + formatDate(date) + ') Tracc Concern (' + dateRange + ')',
                        title: 'Open Tracc Concern Tickets (' + dateRange + ')'
                    },
                    {
                        // Export the table to a CSV file.
                        text: 'CSV',
                        className: 'btn-export',
                        extend: 'csv',
                        filename: '(' + formatDate(date) + ') Tracc Concern (' + dateRange + ')',
                        title: 'Open Tracc Concern Tickets (' + dateRange + ')',
                        customize: function(csv) {
                            var rows = csv.split('\n');

                            var title = 'Open Tracc Concern Tickets (' + dateRange + ')';
                            rows.unshift(title);

                            return rows.join('\n');
                        }
                    } 
                ]
            });

            // Table Tracc Request creation
            // "info" - shows the number of items retrieved.
            // "destroy" - enables the reinstantiation of the table.
            // "searching" - enables/disables searching functionality.
            // "paging" - enables/disables limiting of the data displayed in the table.
            var tableRequest = $('#tblTicketsTraccRequestPrint').DataTable({
                "info": false,
                "destroy": true,
                "searching": false,
                "paging": false,
                "serverSide": true,
                "processing": true,
                "ajax": {
                    "url": "<?= base_url(); ?>DataTables/print_tickets_tracc_request",
                    "type": "POST",
                    "data": {
                        start_date: startDate,
                        end_date: endDate,
                    },
                },
                "columns": [
                    { "data": "ticket_id" },
                    { "data": "requested_by" },
                    { "data": "department" },
                    { "data": "date_requested" },
                    { "data": "company" },
                    { "data": "complete_details" },
                    { "data": "accomplished_by" },
                    { "data": "accomplished_by_date" }
                ],
                "responsive": true,
                "autoWidth": false,
                "lengthChange": false,
                "dom": "<'row'<'col-sm-6'B><'col-sm-6'f>>" + 'rltip',
                "buttons": [
                    {
                        // Exports the table to a pdf document.
                        text: 'PDF',
                        className: 'btn-export',
                        action: function () {
                            // Instantiate jsPDF.
                            var doc = new jsPDF();

                            // Set page number.
                            var page = 1;

                            // Place the table data to a variable.
                            var tableContent = $('#tblTicketsTraccRequestPrint')[0];

                            // Table styles.
                            doc.autoTable({
                                html: tableContent,
                                theme: 'grid',
                                headStyles: {
                                    fillColor: 'white',
                                    textColor: 'black',
                                    fontStyle: 'bold',
                                    fontSize: 10,
                                    lineWidth: 0.5,
                                },
                                bodyStyles: {
                                    fillColor: 'white',
                                    textColor: 'black',
                                    fontSize: 10,
                                    lineWidth: 0.5,
                                },
                                styles: {
                                    cellPadding: 2,
                                    fontSize: 12,
                                    bordered: true,
                                },
                                margin: {
                                    top: 30
                                },
                                didDrawPage: function (data) {
                                    // Document header per page.

                                    // Image logo
                                    var imageUrl = '<?= base_url('assets/images/lifestrong-logo.png'); ?>';
                                    doc.addImage(imageUrl, "PNG", 15, 2, 10, 10);

                                    // Table name
                                    doc.setFontSize(20);
                                    doc.text('Generated Report For Tracc Requests', 28, 10);

                                    // Date range and date printed of document.
                                    doc.setFontSize(12);
                                    doc.text('Date range: ' + dateRange, 15, 20);
                                    doc.text('Date printed: ' + formatDate(date), 15, 26);

                                    // Font size and position of the page number.
                                    doc.setFontSize(10);
                                    doc.text(page.toString(), 200, 290);

                                    // Increment the page number per page.
                                    page++;
                                }
                            });

                            // Open a new tab to view the pdf document.
                            doc.output('dataurlnewwindow', 'Tracc Request ' + '(' + dateRange + ')');
                            doc.save('Tracc Request ' + '(' + dateRange + ')');
                        }
                    },
                    {
                        // Export the table to xlsx
                        text: 'Excel',
                        className: 'btn-export',
                        extend: 'excel',
                        filename: '(' + formatDate(date) + ') Tracc Request (' + dateRange + ')',
                        title: 'Open Tracc Requests (' + dateRange + ')',
                    },
                    {
                        // Export the table to csv
                        text: 'CSV',
                        className: 'btn-export',
                        extend: 'csv',
                        filename: '(' + formatDate(date) + ') Tracc Request (' + dateRange + ')',
                        customize: function(csv) {
                            var rows = csv.split('\n');

                            var title = 'Open Tracc Requests (' + dateRange + ')';
                            rows.unshift(title);

                            return rows.join('\n');
                        }
                    }
                ]
            });
        });
    });
</script>