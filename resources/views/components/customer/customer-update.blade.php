<div class="modal animated zoomIn" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Customer</h5>
            </div>
            <div class="modal-body">
                <form id="save-form">
                    <div class="container">
                        <div class="row">
                            <div class="col-12 p-1">
                                <label class="form-label" >Customer Id</label>
                                <input type="text" class="form-control"readonly id="uCustomerId">
                                <label class="form-label">Customer Name *</label>
                                <input type="text" class="form-control" id="uCustomerName">
                                <label class="form-label">Customer Email *</label>
                                <input type="text" class="form-control" id="uCustomerEmail">
                                <label class="form-label">Customer Mobile *</label>
                                <input type="text" class="form-control" id="uCustomerMobile">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="update-modal-close" class="btn bg-gradient-primary" data-bs-dismiss="modal" aria-label="Close">Close</button>
                <button onclick="updateCustomer()" id="save-btn" class="btn bg-gradient-success" >Update</button>
            </div>
        </div>
    </div>
</div>
<script>
    async function updateCustomer(){
        let id= document.getElementById('uCustomerId').value;
        let name= document.getElementById('uCustomerName').value;
        let email= document.getElementById('uCustomerEmail').value;
        let mobile= document.getElementById('uCustomerMobile').value;
        document.getElementById('update-modal-close').close;
            if (name.length === 0) {
                errorToast('Customer name required')
            } else if (email.length === 0) {
                errorToast('Customer email required')
            } else if (mobile.length === 0) {
                errorToast('Customer mobile required')
            } else {
                showLoader();
                let res = await axios.post("/update-customer", {
                    id: id,
                    name: name,
                    email: email,
                    mobile: mobile
                })
                hideLoader();
                if (res.data === 1) {
                    successToast('Update Successfully')
                    await getCustomerList();
                } else {
                    errorToast('Something went wrong')
                }
            }
        }

</script>
