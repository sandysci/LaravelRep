<?php

namespace App\Services\Payment;

interface PaystackEventType
{
    /**
    *   Events: 
    * @property const CHARGE_SUCCESS	A successful charge was made
    * @property const subscription.create	A subscription has been created
    * @property const subscription.disable	A subscription on your account has been disabled
    * @property const subscription.enable	A subscription on your account has been enabled
    * @property const INVOICE_CREATED	An invoice has been created for a subscription on your account. This usually happens 3 days before the subscription is due or whenever we send the customer their first pending invoice notification
    * @property const INVOICE_UPDATED	An invoice has been updated. This usually means we were able to charge the customer successfully. You should inspect the invoice object returned and take necessary action
    * @property const invoice.failed	An invoice has not been created for the subscription because the customer's payment for the subscription failed
    * @property const TRANSFER_SUCCESS	A successful transfer has been completed
    * @property const transfer.failed	A transfer you attempted has failed
    * @property const paymentrequest.pending	A payment request has been sent to a customer
    * @property const paymentrequest.success	A payment request has been paid for 
    * */

    const INVOICE_UPDATED = 'invoice.update';
    const INVOICE_CREATED = 'invoice.create';
    const CHARGE_SUCCESS = 'charge.success';
    const TRANSFER_SUCCESS = 'transfer.success';
    const BULK_TRANSFER_SUCCESS = 'bulk_transfer.success';
    const BULK_TRANSFER_FAILED = 'bulk_transfer.failed';
    const TRANSFER_FAILED = 'transfer.failed';
    const INVOICE_FAILED = 'invoice.failed';
    const SUBSCRIPTION_ENABLED = 'subscription.enable';
    const SUBSCRIPTION_DISABLED = 'subscription.disable';
    const SUBSCRIPTION_CREATE = 'subscription.create';
}