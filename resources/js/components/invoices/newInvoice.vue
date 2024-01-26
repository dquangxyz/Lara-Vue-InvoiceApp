<script setup>
    import { onMounted, ref } from 'vue';

    let form = ref([])
    let all_customers = ref([])
    let customer_id = ref([])
    let item = ref([])
    let listCart = ref([])
    let showModal = ref(false)
    let listProducts = ref([])

    const indexForm = async () => {
        let res = await axios.get('/api/create_invoice')
        form.value = res.data
    }

    const getAllCustomers = async () => {
        let res = await axios.get('/api/customers')
        all_customers.value = res.data.customers
    }

    const addCart = (item) => {
        const itemCart = {
            id : item.id,
            item_code : item.item_code,
            description : item.description,
            unit_price : item.unit_price,
            quantity : item.quantity
        }
        listCart.push(itemCart)
    }

    const toggleModal = () => {
        showModal.value = !showModal.value
    }

    const getProducts = async () => {
        let res = await axios.get('/api/products')
        listProducts.value = res.data.products
    }

    onMounted(async () => { 
        indexForm()
        getAllCustomers()
        getProducts() 
    })
</script>

<template>
    <div class="container">
        <div class="invoices">
            <div class="card__header">
                <div>
                    <h2 class="invoice__title">New Invoice</h2>
                </div>
                <div>
                    
                </div>
            </div>

            <div class="card__content">
                <div class="card__content--header">
                    <div>
                        <p class="my-1">Customer</p>
                        <select name="" id="" class="input" v-model="customer_id">
                            <option disabled>Select customer</option>
                            <option v-for="customer in all_customers" :key="customer_id">{{customer.firstname}}</option>
                        </select>
                    </div>
                    <div>
                        <p class="my-1">Date</p> 
                        <input id="date" placeholder="dd-mm-yyyy" type="date" class="input" v-model="form.date">
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
                    <div class="table--items2" v-for="(itemcart, i) in listCart" :key="itemcart.id">
                        <p>#{{ itemcart.item_code }} {{ itemcart.description }}</p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.unit_price">
                        </p>
                        <p>
                            <input type="text" class="input" v-model="itemcart.quantity">
                        </p>
                        <p v-if="itemcart.quantity">
                            $ {{ itemcart.quantity * itemcart.unit_price }}
                        </p>
                        <p v-else></p>
                        <p style="color: red; font-size: 24px;cursor: pointer;">
                            &times;
                        </p>
                    </div>
                    <div style="padding: 10px 30px !important;">
                        <button class="btn btn-sm btn__open--modal" @click="toggleModal()">
                            Add New Line
                        </button>
                    </div>
                </div>

                <div class="table__footer">
                    <div class="document-footer" >
                        <p>Terms and Conditions</p>
                        <textarea cols="50" rows="7" class="textarea" ></textarea>
                    </div>
                    <div>
                        <div class="table__footer--subtotal">
                            <p>Sub Total</p>
                            <span>$ 1000</span>
                        </div>
                        <div class="table__footer--discount">
                            <p>Discount</p>
                            <input type="text" class="input">
                        </div>
                        <div class="table__footer--total">
                            <p>Grand Total</p>
                            <span>$ 1200</span>
                        </div>
                    </div>
                </div>

            
            </div>
            <div class="card__header" style="margin-top: 40px;">
                <div>
                    
                </div>
                <div>
                    <a class="btn btn-secondary">
                        Save
                    </a>
                </div>
            </div>
            
        </div>
        <!--==================== add modal items ====================-->
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
                    <button class="btn btn-light mr-2 btn__close--modal" @click="toggleModal">
                        Cancel
                    </button>
                    <button class="btn btn-light btn__close--modal ">Save</button>
                </div>
            </div>
        </div>
    </div>
</template>