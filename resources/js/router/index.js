import { createRouter, createWebHistory } from "vue-router";
import invoiceIndex from '../components/invoices/index.vue';
import invoiceNew from '../components/invoices/newInvoice.vue';
import invoiceDetails from '../components/invoices/detailedInvoice.vue';
import notFound from '../components/NotFound.vue';

const routes = [
    {
        path: '/',
        component: invoiceIndex
    },
    {
        path: '/invoice/new',
        component: invoiceNew
    },
    {
        path: '/invoice/details/:id',
        component: invoiceDetails,
        props: true
    },
    {
        path: '/:pathMatch(.*)*',
        component: notFound
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router