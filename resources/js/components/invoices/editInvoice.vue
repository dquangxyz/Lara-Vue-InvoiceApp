<script setup>
    import { onMounted, ref } from 'vue';
    import { useRouter } from 'vue-router';

    const router = useRouter();

    let form = ref({ id: '' })
    let all_customers = ref([])
    let customer_id = ref([])

    let showModal = ref(false)
    let listProducts = ref([])

    const props = defineProps({
        id : { type: String, default:''}
    })

    // EDIT: to delete an item
    const getAllCustomers = async () => {
        let res = await axios.get('/api/customers')
        all_customers.value = res.data.customers
    }

    const getInvoice = async () => {
        let res = await axios.get(`/api/edit_invoice/${props.id}`)
        console.log("res", res.data.invoice)
        form.value = res.data.invoice
    }

    const deleteInvoiceItem = async (id, i) => {
        form.value.invoice_items.splice(i, 1)
        if (id != undefined){
            await axios.get('/api/delete_invoice_item/' + id)
        }
    }

    // EDIT: to add an item
    const toggleModal = () => {
        showModal.value = !showModal.value
    }

    const getProducts = async () => {
        let res = await axios.get('/api/products')
        listProducts.value = res.data.products
    }

    const addCart = (item) => {
        const itemCart = {
            product_id : item.id,
            item_code : item.item_code,
            description : item.description,
            unit_price : item.unit_price,
            quantity : item.quantity
        }
        form.value.invoice_items.push(itemCart)
        toggleModal()
    }

    const calculateSubTotal = () => {
        let subTotal = 0
        if(form.value.invoice_items){
            form.value.invoice_items.map((data) => (
                subTotal = subTotal + (data.quantity * data.unit_price)
            ))
        }
        
        return subTotal
    }

    const calculateGrandTotal = () => {
        let subTotal = calculateSubTotal()
        return (subTotal - subTotal * form.value.discount/100)
    }

    // save the changes

    const onEdit = () => {
        if(form.value.invoice_items.length > 0){
            const subTotal = calculateSubTotal()
            const grandTotal = calculateGrandTotal()

            console.log("subtotal", subTotal, "grandTotal", grandTotal)

            const formData = new FormData()
            formData.append('invoice_item', JSON.stringify(form.value.invoice_items))
            formData.append('customer_id', form.value.customer_id)
            formData.append('date', form.value.date)
            formData.append('due_date', form.value.due_date)
            formData.append('number', form.value.number)
            formData.append('reference', form.value.reference)
            formData.append('discount', form.value.discount)
            formData.append('sub_total', subTotal)
            formData.append('total', grandTotal)
            formData.append('terms_and_conditions', form.value.terms_and_conditions)


            axios.post(`/api/update_invoice/${form.value.id}`, formData)
            form.value.invoice_items = []
            router.push('/')
        }
    }


    onMounted(async () => {
        await getInvoice()
        await getAllCustomers()
        await getProducts()
    })

</script>

<template setup>
    <div class="container">
        <div class="invoices">
            
            <div class="card__header">
                <div>
                    <h2 class="invoice__title">Edit Invoice</h2>
                </div>
                <div>
                    
                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="form.customer_id">
                            <option disabled>Select customer</option>
                            <option :value="customer.id" v-for="customer in all_customers" :key="customer.id">
                                {{customer.firstname}}
                            </option>
                        </select>sssssss
                    </div>
                    <div>
                        <p class="my-1">Date</p> 
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date"> <!---->
                        <p class="my-1">Due Date</p> 
                        <input id="due_date" type="date" class="input" v-model="form.due_date">
                    </div>
                    <div>
                        <p class="my-1">Numero</p> 
                        <input type="text" class="input" v-model="form.number"> 
                        <p class="my-1">Reference(Optional)</p> 
                        <input type="text" class="input" v-model="form.reference">
                    </div>
                </div>
                <br><br>
                <div class="table">

                    <div class="table--heading2">
                        <p>Item Description</p>
                        <p>Unit Price</p>
                        <p>Qty</p>
                        <p>Total</p>
                        <p></p>
                    </div>
        
                    <!-- item 1 -->
                    <div class="table--items2" v-for="(item, i) in form.invoice_items" :key="item.id">
                        <p v-if="item.product"># {{ item.product.item_code }} {{ item.product.description }} </p>
                        <p v-else># {{ item.item_code }} {{ item.description }} </p>

                        <p>
                            <input type="text" class="input" v-model="item.unit_price" >
                        </p>
                        <p>
                            <input type="text" class="input" v-model="item.quantity" >
                        </p>
                        <p>
                            $ {{ item.unit_price * item.quantity }}
                        </p>
                        <p style="color: red; font-size: 24px;cursor: pointer;" @click="deleteInvoiceItem(item.id, i)">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="toggleModal()">Add New Line</button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer" >
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" v-model="form.terms_and_conditions" ></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ {{ calculateSubTotal() }}</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount (%)</p>
                            <input type="text" class="input" v-model="form.discount">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ {{ calculateGrandTotal() }}</span>
                        </div>
                    </div>
                </div>

            
            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>
                    
                </div>
                <div>
                    <a class="btn btn-secondary"  @click="onEdit()">
                        Save
                    </a>
                </div>
            </div>

            <div class="modal main__modal " :class="{show: showModal}">
                <div class="modal__content">
                    <span class="modal__close btn__close--modal" @click="toggleModal()">×</span>
                    <h3 class="modal__title">Add Item</h3>
                    <hr><br>
                    <div class="modal__items">
                        <ul style="list-style:none">
                        <li class="list-product-item" v-for="(item, i) in listProducts" :key="item.id">
                            <p>{{ i+1 }}</p>
                            <a href="#">{{ item.item_code }} {{ item.description }}</a>
                            <button class="btn-add-item" @click="addCart(item)">+</button>
                        </li>
                    </ul> 
                    </div>
                    <br><hr>
                    <div class="model__footer">
                        <button class="btn btn-light mr-2 btn__close--modal" @click="toggleModal()">
                            Cancel
                        </button>
                        <button class="btn btn-light btn__close--modal ">Save</button>
                    </div>
                    
                </div>
            </div>
            
            <br><br><br>
            
        </div>
    </div>
</template>