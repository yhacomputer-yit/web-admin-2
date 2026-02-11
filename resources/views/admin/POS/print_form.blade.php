
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset("css/bootstrap.min.css")}}">
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet"> --}}
    {{-- <script src="{{asset("js/jquery_printPage.js")}}"></script> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script> --}}
    <link rel="stylesheet" href="{{asset("js/jquery-3.7.1.min.js")}}">
    <script src="{{asset("js/jquery-3.7.1.min.js")}}"></script>
    <script type="text/javascript" src="js/jquery.printPage.js"></script>
    <script type="text/javascript" src="http://www.position-absolute.com/creation/print/jquery.printPage.js"></script>
    <title>Voucher Printing Form</title>
</head>
  

<style>
    .cancel_btn{
        background: #ff6c0f;
        color: white;
        border: none;
        outline: none;
        margin-top: 10px;
        padding: 5px 15px;
        border-radius: 10px;
    }
    .voucher span,.voucher_date span{
        font-size: 16px;
        font-weight: bold;
    }
    label{
        font-size: 18px;
        color: black;
    }
    input{
        border: 1px solid #ff6c0f;
    }
    table,tr,td,th{
        font-size: 18px;
        color: black;
    }
    .cellClass_id{
        display: none;
    }
    #change_Ui{
        background-color: #ff6c0f;
        border: 2px solid #ff6c0f;
        color: white;
    }
    #change_Ui:hover{
        color: #ff6c0f;
        background: none;
    }
</style>
<body>

<div class="container-fluid px-5">
    <div style="background-color: #ff6c0f; color:white; padding: 10px;" class="card-header p-0 d-flex align-items-center justify-content-center">
        <h4>YHA POS System</h4>
        <img width="90px" src="{{ asset('image/logo/logo.png') }}" alt="">
    </div>
    <div class="row justify-content-center mt-3">
        <div class="col-xl-3 col-12">
            <div class="card">
                
                <div class="card-body">
                    <form id="voucherForm">
                        <!-- Voucher Number and Date -->
                        <div class="form-row mb-3">
                            <div class="form-group col-md-6">
                                <label class="fs-6" for="voucherNumber">Voucher Number:</label>
                                <input required type="text" id="voucherNumber" class="form-control" value="{{ rand(1000, 9999) }}" readonly>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="fs-6" for="voucherDate">Voucher Date:</label>
                                <input required type="date" id="voucherDate" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                            </div>
                        </div>
                        <!-- Student Names -->
                        <div class="form-group mb-3">
                            <label class="fs-6" for="studentNames">Student Names</label>
                            <input required type="text" id="studentNames" class="form-control">
                        </div> 

                        <!-- Class Selection -->
                        <div class="form-group mb-3">
                            <label class="fs-6" for="classSelect">Select Course:</label>
                            <select id="classSelect" class="form-control" onchange="updatePrice()">
                                @foreach ($courses as $course)
                                    <option value="{{ $course->id }}" data-normal-price="{{ $course->normal_price }}" data-special-price="{{ $course->special_price }}">
                                        {{ $course->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Times Selection -->
                        <div class="form-group mb-3">
                            <label class="fs-6" for="sectionSelect">Select Time:</label>
                            <select id="sectionSelect" class="form-control" onchange="updatePrice()">
                                @foreach ($sections as $section)
                                    <option value="{{ $section->id }}">
                                        {{ $section->name }} {{ $section->start_hour }}:{{ $section->start_minute }}-{{ $section->end_hour }}:{{ $section->end_minute }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3 d-none">
                            <label class="fs-6" for="studentNames">Course Id</label>
                            <input required type="text" id="classid" class="form-control">
                        </div>

                        <div class="form-check mb-3 d-flex">
                            <div>
                                 <input class="form-check-input" type="radio" name="category" id="category1" value="normal" checked onchange="updatePrice()">
                                <label class="fs-6" class="form-check-label" for="category1">
                                    Normal Fees
                                </label>
                            </div>
                           
                            <div class="ms-5">
                                <input style="" class="form-check-input" type="radio" name="category" id="category2" value="special" onchange="updatePrice()">
                                <label class="fs-6" style="" class="form-check-label" for="category2">
                                    Special Fees
                                </label>
                            </div>
                            
                        </div>

                        <!-- Class Start Date -->
                        <div class="form-group mb-3">
                            <label class="fs-6" for="classStartDate">Course Start Date:</label>
                            <input required type="date" id="classStartDate" class="form-control">
                        </div>


                        <!-- Class Price -->
                        <div class="form-group mb-3">
                            <label class="fs-6" for="classPrice">Course Fee:</label>
                            <input required type="number" id="classPrice" class="form-control">
                        </div>

                        <div class="d-flex justify-content-between">
                            <div class="form-group">
                                <a href="{{ route('admin.home') }}"><button type="button" style="border: 1px solid #ff6c0f; color: #ff6c0f" class="btn">Back</button></a>
                            </div>
                            <!-- Submit Button -->
                            <div class="form-group">
                                <button id="change_Ui" type="button" style="float: right;" class="btn" onclick="generateTable()">Add Voucher Record</button>
                            </div>
                        </div>
                        
                    </form> 
                </div>
            </div>
        </div>
       
        <div class="col-xl-9 col-12">
            <form id="form1" method="POST">
                @csrf
            <div class="vou d-flex align-items-center justify-content-between mb-3">
                <div class="voucher_show">
                    <span>Voucher:</span>
                    <span id="voucher_no">0</span>
                </div>
                <div class="voucher_date_show">
                    <span>Voucher Date:</span>
                    <span class="vouc_date" id="voucher_date"></span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="myTable">
                    <thead>
                        <tr>
                            <th class="fs-6 text-black">Student Name</th>
                            <th class="fs-6 text-black">Start Date</th>
                            <th class="fs-6 text-black">Time</th>
                            <th class="fs-6 text-black">Course</th>
                            <th class="fs-6 text-black">Fees</th>
                            <th class="fs-6 text-black">Cancel</th>
                        </tr>
                        
                    </thead>
                    <tbody id="tableBody">

                    </tbody>
                </table>
            </div>

            <div class="balance d-flex justify-content-end mb-3">
                <div class="form-row d-inline-block">
                    <div class="d-flex justify-content-between align-items-center mb-1 form-group col-md-12">
                        <label class="fs-6" for="totalPrice">Total Fee:</label>
                        <input required type="text" id="totalPrice" class="form-control w-50" readonly>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1 form-group col-md-12">
                        <label class="fs-6" for="discount">Discount:</label>
                        <input required type="text" id="discount" class="form-control w-50" value="0" onchange="updateTotalPrice()" onblur="updateTotalPrice()">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1 form-group col-md-12">
                        <label class="fs-6" for="subtotal">Paid:</label>
                        <input required type="text" id="subtotal" class="form-control p w-50" value="0" onchange="updateTotalPrice()" onblur="updateTotalPrice()">
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-1 form-group col-md-12">
                        <label class="fs-6" for="Balance">Balance:</label>
                        <input required type="text" id="balance" class="form-control b w-50" readonly>
                    </div>
                    <div class="d-none justify-content-between align-items-center mb-1 form-group col-md-12">
                        <label class="fs-6" for="status">Status:</label>
                        <input required type="text" id="status" name="status" class="form-control b w-50" value="0">
                    </div>
                    
                </div>
            </div>
            <div style="float: left;" class="form-group">
                <button style="background-color:#ff6c0f; color:white;" type="button" id="submitButton" class="btn">Save To Database</button>
            </div>
            <div style="float: right;" class="form-group">
                <a id="change_Ui" href="{{route('invoice')}}" class="btnpri btn">Print</a>
            </div>
        </form>

        </div>
    </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>


<script>

function generateTable() {
    var studentNames = document.getElementById("studentNames").value.split(',');
    var classSelect = document.getElementById("classSelect");
    var sectionSelect = document.getElementById("sectionSelect");
    var classPrice = parseFloat(document.getElementById("classPrice").value);
    var classStartDate = document.getElementById("classStartDate").value;

    var tableBody = document.getElementById("tableBody");

    studentNames.forEach(function (name) {
        var newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td class="fs-6 cellname">${name.trim()}</td>
            <td class="fs-6 class_date">${classStartDate.trim()}</td>
            <td class="fs-6 cellclass">${classSelect.options[classSelect.selectedIndex].text}</td>
            <td class="fs-6 cellSection">${sectionSelect.options[sectionSelect.selectedIndex].text}</td>
            <td class="fs-6 cellPrice">${classPrice.toFixed(2)}</td>
            <td><button class="cancel_btn">Cancel</button></td>
        `;
        tableBody.appendChild(newRow);
    });

    // Add event listeners for the cancel buttons
    document.querySelectorAll(".cancel_btn").forEach(function(button) {
        button.addEventListener("click", function () {
            // Remove the row when the "Cancel" button is clicked
            this.parentElement.parentElement.remove();
            // Update total fee after removing the row
            updateTotalPrice();
        });
    });

    updateTotalPrice();
    
    var voucherNumber = document.getElementById('voucherNumber').value;
    document.getElementById('voucher_no').innerText = voucherNumber;
}



function update_status(){
    let status = document.querySelector('#status');
    let balance = document.querySelector('#balance');

    if( balance.value == 0.00 ){
        // console.log('balance is 0');
        status.value = '1';
    } else{
        // console.log('balance is not 0');
        status.value = '0';
    }
}

function updateTotalPrice() {
    var totalFee = 0;
    var rows = document.querySelectorAll("#tableBody tr");

    rows.forEach(function (row) {
        var priceCell = row.querySelector("td:nth-child(5)");
        if (priceCell) {
            totalFee += parseFloat(priceCell.textContent);
        }
    });

    var discount = parseFloat(document.getElementById("discount").value) || 0;
    var paid = parseFloat(document.getElementById("subtotal").value) || 0;

    var balanceInput = document.getElementById("balance");
    var totalPriceInput = document.getElementById("totalPrice");

    var balance = totalFee - discount - paid;

    balanceInput.value = balance.toFixed(2);
    totalPriceInput.value = totalFee.toFixed(2);

    update_status();

}

function updatePrice() {
        var classSelect = document.getElementById("classSelect");
        var classPriceInput = document.getElementById("classPrice");
        var selectedOption = classSelect.options[classSelect.selectedIndex];
        var selectedCategory = document.querySelector('input[name="category"]:checked').value;

        var priceAttribute = (selectedCategory === 'normal') ? 'data-normal-price' : 'data-special-price';

        var classPrice = selectedOption.getAttribute(priceAttribute) || 0;

        classPriceInput.value = classPrice;

        document.getElementById("classid").value = selectedOption.value;     
        

    }


// Function to update total fee
function sendTableDataToController() {
    var csrfToken = '{{ csrf_token() }}';
    var rows = document.querySelectorAll("#tableBody tr");
    var tableData = [];

    rows.forEach(function (row) {
        var rowData = {
            'name': row.querySelector(".cellname").textContent,
            'classDate': row.querySelector(".class_date").textContent,
            'className': row.querySelector(".cellclass").textContent,
            'section': row.querySelector(".cellSection").textContent,
            'classPrice': row.querySelector(".cellPrice").textContent,
            'voucher_no': document.querySelector("#voucher_no").textContent,
            'voucher_date': document.querySelector("#voucher_date").textContent,

            'totalPrice': document.querySelector("#totalPrice").value,
            'discount': document.querySelector("#discount").value,
            'subtotal': document.querySelector("#subtotal").value,
            'balance': document.querySelector("#balance").value,
            'studentNames': [row.querySelector(".cellname").textContent] // Wrap the name in an array
        };
        tableData.push(rowData);
    });

    var csrfToken = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $.ajax({
            url: '/datasend',
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            
            data: { tableData: tableData },
            success: function(response) {
                //console.log("Response here:", response);
                window.location.href = '/invoice?token=' + response.token;
            },
            error: function(error) {
                console.error(error);
            }
        });
    });
}

$(document).ready(function () {
    $('.btnpri').on('click', function () {
        sendTableDataToController();
    });
});
 


</script>

<script>
// inserting into database
var csrfToken = document.querySelector('meta[name="csrf-token"]').content;

document.getElementById('submitButton').addEventListener('click', function () {
    let studentNames = [document.getElementById("studentNames").value];
    let classSelect = document.getElementById("classSelect");
    let sectionSelect = document.getElementById("sectionSelect");
    let classPrice = parseFloat(document.getElementById("classPrice").value);
    let classStartDate = document.getElementById("classStartDate").value;
    let voucher_no = document.getElementById("voucher_no").textContent;
    let classid = parseInt(document.getElementById("classid").value); // Ensure this is an integer

    let totalPrice = parseFloat(document.getElementById("totalPrice").value) || 0;
    let discount = parseFloat(document.getElementById("discount").value) || 0;
    let subtotal = parseFloat(document.getElementById("subtotal").value) || 0;
    let balance = parseFloat(document.getElementById("balance").value) || 0;
    let status = document.getElementById("status").value;

    let tableRows = document.getElementById("myTable").getElementsByTagName('tr');
    let tableData = [];

    for (let i = 1; i < tableRows.length; i++) {
        let currentRow = tableRows[i];
        let cells = currentRow.getElementsByTagName('td');

        let studentName = cells[0].textContent.trim();
        let classStartDate = cells[1].textContent.trim();
        let classTime = parseInt(cells[2].getAttribute('data-section-id')); // Ensure this is an integer
        let className = cells[3].textContent.trim();
        let classPrice = parseFloat(cells[4].textContent.trim());

        tableData.push({
            studentName: studentName,
            classStartDate: classStartDate,
            classTime: classTime, // Ensure this is an integer
            className: className,
            classPrice: classPrice.toFixed(2),
            classid: classid
        });
    }

    let formData = {
        studentNames: studentNames,
        classStartDate: classStartDate,
        voucher_no: voucher_no,
        className: classSelect.options[classSelect.selectedIndex].text,
        classTime: parseInt(sectionSelect.value), // Ensure this is an integer
        classPrice: classPrice.toFixed(2),
        totalPrice: totalPrice.toFixed(2),
        discount: discount.toFixed(2),
        subtotal: subtotal.toFixed(2),
        balance: balance.toFixed(2),
        status: status,
        tableData: tableData,
        classid: classid
    };

    $.ajax({
        url: '/insertdata',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
        },
        data: JSON.stringify(formData),
        success: function (response) {
            alert("Voucher is recored");
            console.log("Response Data:", response);
        },
        error: function (xhr) {
            console.log("XHR Object:", xhr);
            if (xhr.responseJSON && xhr.responseJSON.error) {
                console.error("Server error:", xhr.responseJSON.error);
            } else {
                console.error("An unexpected error occurred.");
            }
        }
    });
});


const currentDate = new Date();
const options = {
  day: 'numeric',
  month: 'numeric',
  year: 'numeric',
};
const formattedDate = currentDate.toLocaleDateString(undefined, options);
let vouc_date = document.querySelector(".vouc_date");
vouc_date.textContent = formattedDate;



</script>

