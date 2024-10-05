<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12">
            <div class="card px-5 py-5">
                <div class="row justify-content-between">
                    <div class="align-items-center col">
                        <h4>Customer</h4>
                    </div>
                    <div class="align-items-center col">
                        <button data-bs-toggle="modal" data-bs-target="#create-modal" class="float-end btn m-0 bg-gradient-primary">Create</button>
                    </div>
                </div>
                <hr class="bg-secondary"/>
                <div class="table-responsive">
                    <table class="table" id="tData">
                        <thead>
                        <tr class="bg-light">
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="tList">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    getCustomerList();


    async function getCustomerList() {


        showLoader();
        let res=await axios.get("/list-customer");
        hideLoader();

        let tList=$("#tList");
        let tData=$("#tData");

        tData.DataTable().destroy();
        tList.empty();

        res.data.forEach(function (item,index){
            let row=`<tr>
                    <td>${index+1}</td>
                    <td>${item['name']}</td>
                    <td>${item['email']}</td>
                    <td>${item['mobile']}</td>
                    <td>
                        <button data-id="${item['id']}"  data-name="${item['name']}" data-email="${item['email']}" data-mobile="${item['mobile']}"
                                         class="btn editBtn btn-sm btn-outline-success">Edit</button>
                        <button data-id="${item['id']}"  data-name="${item['name']}"  class="btn deleteBtn btn-sm btn-outline-danger">Delete</button>
                    </td>
                 </tr>`
            tList.append(row)
        })
        $('.editBtn').on('click',function (){
            let id= $(this).data('id');
            let name = $(this).data('name');
            let email= $(this).data('email');
            let mobile = $(this).data('mobile');
            $("#update-modal").modal('show');
            $("#uCustomerId").val(id)
            $("#uCustomerName").val(name)
            $("#uCustomerEmail").val(email)
            $("#uCustomerMobile").val(mobile)
        })
        $('.deleteBtn').on('click',function (){
            let id= $(this).data('id');
            $("#delete-c-modal").modal('show');
            $("#D-customerId").val(id)
        })
        new DataTable('#tData',{
            order:[[0,'asc']],
            lengthMenu:[5,10,15,20,30]
        });

    }

</script>
