@extends('layout.side-nav')
@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <div class="row">
                        <div class="col-8">
                            <span class="text-bold text-dark">BILLED TO </span>
                            <p class="text-xs mx-0 my-1">Name:  <span id="CName"></span> </p>
                            <p class="text-xs mx-0 my-1">Email:  <span id="CEmail"></span></p>
                            <p class="text-xs mx-0 my-1">User ID:  <span id="CId"></span> </p>
                        </div>
                        <div class="col-4">
                            <img class="w-50" src="{{"images/logo.png"}}">
                            <p class="text-bold mx-0 my-1 text-dark">Invoice  </p>
                            <p class="text-xs mx-0 my-1">Date: {{ date('Y-m-d') }} </p>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12">
                            <table class="table w-100" id="invoiceTable">
                                <thead class="w-100">
                                <tr class="text-xs">
                                    <td>Name</td>
                                    <td>Qty</td>
                                    <td>Total</td>
                                    <td>Remove</td>
                                </tr>
                                </thead>
                                <tbody  class="w-100" id="invoiceList">

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="mx-0 my-2 p-0 bg-secondary"/>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-bold text-xs my-1 text-dark"> TOTAL: <i class="bi bi-currency-dollar"></i> <span id="total"></span></p>
                            <p class="text-bold text-xs my-2 text-dark"> PAYABLE: <i class="bi bi-currency-dollar"></i>  <span id="payable"></span></p>
                            <p class="text-bold text-xs my-1 text-dark"> VAT(5%): <i class="bi bi-currency-dollar"></i>  <span id="vat"></span></p>
                            <p class="text-bold text-xs my-1 text-dark"> Discount: <i class="bi bi-currency-dollar"></i>  <span id="discount"></span></p>
                            <span class="text-xxs">Discount(%):</span>
                            <input onkeydown="return false" value="0" min="0" type="number" step="0.25" onchange="DiscountChange()" class="form-control w-40 " id="discountP"/>
                            <p>
                                <button onclick="createInvoice()" class="btn  my-3 bg-gradient-primary w-40">Confirm</button>
                            </p>
                        </div>
                        <div class="col-12 p-2">

                        </div>

                    </div>
                </div>
            </div>



            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table  w-100" id="productTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Product</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="productList">

                        </tbody>
                    </table>
                </div>
            </div>



            <div class="col-md-4 col-lg-4 p-2">
                <div class="shadow-sm h-100 bg-white rounded-3 p-3">
                    <table class="table table-sm w-100" id="customerTable">
                        <thead class="w-100">
                        <tr class="text-xs text-bold">
                            <td>Customer</td>
                            <td>Pick</td>
                        </tr>
                        </thead>
                        <tbody  class="w-100" id="customerList">

                        </tbody>
                    </table>
                </div>
            </div>
    </div>




    <div class="modal animated zoomIn" id="create-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Add Product</h6>
                </div>
                <div class="modal-body">
                    <form id="add-form">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 p-1">
                                    <label class="form-label">Product ID *</label>
                                    <input type="text" class="form-control" readonly id="PId">
                                    <label class="form-label mt-2">Product Name *</label>
                                    <input type="text" class="form-control" readonly id="PName">
                                    <label class="form-label mt-2">Product Price *</label>
                                    <input type="text" class="form-control" id="PPrice">
                                    <label class="form-label mt-2">Product Qty *</label>
                                    <input type="text" class="form-control" id="PQty">
                                    <label class="form-label mt-2 d-none">Product Quantity *</label>
                                    <input type="text" class="form-control d-none" id="PQuantity">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                    <button onclick="add()" id="save-btn" class="btn bg-gradient-success">Add</button>
                </div>
            </div>
        </div>
    </div>

    <script>

        productTable();
        customerList();

        let InvoiceItemList=[];


        async function productTable() {
            showLoader();
            let res = await axios.get("/list-product");
            hideLoader();

            let tableData = $("#productTable");
            let tableList = $("#productList");

            tableData.DataTable().destroy();
            tableList.empty();

            res.data.forEach(function (item, index) {
                let row = `<tr>
                    <td><img class="w-10 h-auto" alt="" src="${item['image']}">${item['name']}</td>
                    <td>
                        <button data-id="${item['id']}" data-name="${item['name']}" data-price="${item['price']}"
                             data-quantity="${item['quantity']}"  class="btn addBtn btn-sm btn-outline-dark">Add</button>
                    </td>
                 </tr>`
                tableList.append(row)
            });

            $('.addBtn').on('click',function (){

                let id= $(this).data('id');
                let pName = $(this).data('name');
                let price= $(this).data('price');
                let quantity= $(this).data('quantity');

                document.getElementById('PName').value=pName;
                document.getElementById('PPrice').value=price;
                document.getElementById('PId').value=id;
                document.getElementById('PQuantity').value=quantity;

                $("#create-modal").modal('show');
            })

            new DataTable('#tableData',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }

        async function customerList() {

            showLoader();
            let res = await axios.get("/list-customer");
            hideLoader();

            let cData = $("#customerTable");
            let cList = $("#customerList");

            cData.DataTable().destroy();
            cList.empty();

            res.data.forEach(function (item, index) {
                let row =
                    `<tr>
                         <td>${item['name']}</td>
                         <td>
                        <button data-id="${item['id']}"  data-name="${item['name']}" data-email="${item['email']}" data-mobile="${item['mobile']}"
                                         class="btn selectBtn btn-sm btn-outline-dark">Select</button>
                       </td>
                 </tr>`
                cList.append(row)
            })
            $('.selectBtn').on('click',function (){
                let id= $(this).data('id');
                let name = $(this).data('name');
                let email= $(this).data('email');
                let mobile = $(this).data('mobile');
                document.getElementById('CName').innerHTML=name;
                document.getElementById('CEmail').innerHTML=email;
                document.getElementById('CId').innerHTML=id;
            })
            new DataTable('#cData',{
                order:[[0,'desc']],
                scrollCollapse: false,
                info: false,
                lengthChange: false
            });
        }

     async   function add() {
            let PId = document.getElementById('PId').value;
            let PName = document.getElementById('PName').value;
            let PPrice = document.getElementById('PPrice').value;
            let PQty = document.getElementById('PQty').value;
            let quantity = document.getElementById('PQuantity').value
            let PTotalPrice = (parseFloat(PPrice) * parseFloat(PQty)).toFixed(2);

             if(parseFloat(quantity)>parseFloat(PQty)) {
                 if (PId.length === 0) {
                     errorToast("Product ID Required");
                 } else if (PName.length === 0) {
                     errorToast("Product Name Required");
                 } else if (PPrice.length === 0) {
                     errorToast("Product Price Required");
                 } else if (PQty.length === 0) {
                      errorToast("Product Quantity Required");
                 } else {
                      let item = {
                          product_name: PName,
                          product_id: PId,
                          qty: PQty,
                          sale_price: PTotalPrice
                         };
                     InvoiceItemList.push(item);
                     console.log(InvoiceItemList);
                     $('#create-modal').modal('hide')
                     ShowInvoiceItem();
                 }
             }else {
              errorToast('Shortage of Product');
             }
        }


       async function updateQuantity() {
            let id = document.getElementById('PId').value;
            showLoader();
            let res=await axios.post("/product-by-id",{id:id});
            hideLoader();

        }




        function ShowInvoiceItem() {

            let invoiceList=$('#invoiceList');

            invoiceList.empty();

            InvoiceItemList.forEach(function (item,index) {
                let row=`<tr class="text-xs">
                        <td>${item['product_name']}</td>
                        <td>${item['qty']}</td>
                        <td>${item['sale_price']}</td>
                        <td><a data-index="${index}" class="btn remove text-xxs px-2 py-1  btn-sm m-0">Remove</a></td>
                     </tr>`
                invoiceList.append(row)
            })

            CalculateGrandTotal();

            $('.remove').on('click', async function () {
                let index= $(this).data('index');
                removeItem(index);
            })
        }

        function removeItem(index) {
            InvoiceItemList.splice(index,1);
            ShowInvoiceItem();
        }

        function CalculateGrandTotal(){
            let Total=0;
            let Vat=0;
            let Payable=0;
            let Discount=0;
            let discountPercentage=(parseFloat(document.getElementById('discountP').value));
            InvoiceItemList.forEach((item,index)=>{
                Total=Total+parseFloat(item['sale_price'])
            })

            if(discountPercentage===0){
                Vat= ((Total*5)/100).toFixed(2);
            }
            else {
                Discount=((Total*discountPercentage)/100).toFixed(2);
                Total=(Total-Discount).toFixed(2);
                Vat= ((Total*5)/100).toFixed(2);
            }

            Payable=(parseFloat(Total)+parseFloat(Vat)).toFixed(2);


            document.getElementById('total').innerText=Total;
            document.getElementById('payable').innerText=Payable;
            document.getElementById('vat').innerText=Vat;
            document.getElementById('discount').innerText=Discount;
        }

        function DiscountChange() {
            CalculateGrandTotal();
        }


        async  function createInvoice() {
            let total=document.getElementById('total').innerText;
            let discount=document.getElementById('discount').innerText
            let vat=document.getElementById('vat').innerText
            let payable=document.getElementById('payable').innerText
            let CId=document.getElementById('CId').innerText;


            let Data={
                "total":total,
                "discount":discount,
                "vat":vat,
                "payable":payable,
                "customer_id":CId,
                "products":InvoiceItemList
            }


            if(CId.length===0){
                errorToast("Customer Required !")
            }
            else if(InvoiceItemList.length===0){
                errorToast("Product Required !")
            }
            else{

                showLoader();
                let res=await axios.post("/invoice-create",Data)
                hideLoader();
                if(res.data===1){
                    window.location.href='/invoicePage'
                    successToast("Invoice Created");
                }
                else{
                    errorToast("Something Went Wrong")
                }
            }

        }





    </script>


@endsection
