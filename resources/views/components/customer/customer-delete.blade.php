<div class="modal animated zoomIn" id="delete-c-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">Delete Customer!!</h5>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <p class="font-weight-bold">If you delete once, can not get back.</p>
                                <input type="text" class="form-control d-none" id="D-customerId">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="delete-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="deleteCustomer()" id="save-btn" class="btn bg-gradient-success" >Delete</button>
            </div>
        </div>
    </div>
</div>

<script>
    async function deleteCustomer(){
        let id=document.getElementById('D-customerId').value;
        document.getElementById('delete-modal-close').click();
        showLoader();
        let res = await axios.post("/delete-customer",{
            id:id,
        })
        hideLoader()
        if (res.data===1){
            successToast('Delete Successfully')
            await getCustomerList();
        }
        else{
            errorToast('Something went wrong')
        }
    }
</script>
