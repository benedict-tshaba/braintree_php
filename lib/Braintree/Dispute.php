<?php
namespace Braintree;

/**
 * Creates an instance of Dispute as returned from a transaction
 *
 *
 * @package    Braintree
 *
 * @property-read string $amount
 * @property-read string $currencyIsoCode
 * @property-read date   $receivedDate
 * @property-read string $reason
 * @property-read string $status
 * @property-read string $disbursementDate
 * @property-read object $transactionDetails
 */
class Dispute extends Base
{
    protected $_attributes = [];

    /* Dispute Status */
    const OPEN  = 'open';
    const WON  = 'won';
    const LOST = 'lost';

    /* deprecated; for backwards compatibilty */
    const Open  = 'open';

    /* Dispute Reason */
    const CANCELLED_RECURRING_TRANSACTION = "cancelled_recurring_transaction";
    const CREDIT_NOT_PROCESSED            = "credit_not_processed";
    const DUPLICATE                       = "duplicate";
    const FRAUD                           = "fraud";
    const GENERAL                         = "general";
    const INVALID_ACCOUNT                 = "invalid_account";
    const NOT_RECOGNIZED                  = "not_recognized";
    const PRODUCT_NOT_RECEIVED            = "product_not_received";
    const PRODUCT_UNSATISFACTORY          = "product_unsatisfactory";
    const TRANSACTION_AMOUNT_DIFFERS      = "transaction_amount_differs";
    const RETRIEVAL                       = "retrieval";

    /* Dispute Kind */
    const CHARGEBACK      = 'chargeback';
    const PRE_ARBITRATION = 'pre_arbitration';
    // RETRIEVAL for kind already defined under Dispute Reason

    protected function _initialize($disputeAttribs)
    {
        $this->_attributes = $disputeAttribs;

        if (isset($disputeAttribs['transaction'])) {
			$transactionDetails = new Dispute\TransactionDetails($disputeAttribs['transaction']);
            $this->_set('transactionDetails', $transactionDetails);
            $this->_set('transaction', $transactionDetails);
        }

        if (isset($disputeAttribs['evidence'])) {
            $evidenceArray = [];
            foreach ($disputeAttribs['evidence'] as $evidence) {
                array_push($evidenceArray, new Dispute\EvidenceDetails($evidence));
            }
            $this->_set('evidence', $evidenceArray);
        }

        if (isset($disputeAttribs['status_history'])) {
            $statusHistoryArray = [];
            foreach ($disputeAttribs['status_history'] as $statusHistory) {
                array_push($statusHistoryArray, new Dispute\StatusHistoryDetails($statusHistory));
            }
            $this->_set('status_history', $statusHistoryArray);
        }

        if (isset($disputeAttribs['transaction'])) {
            $this->_set('transaction',
                new Dispute\TransactionDetails($disputeAttribs['transaction'])
            );
        }
    }

    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }

    public function  __toString()
    {
        $display = [
            'amount', 'reason', 'status',
            'replyByDate', 'receivedDate', 'currencyIsoCode'
            ];

        $displayAttributes = [];
        foreach ($display AS $attrib) {
            $displayAttributes[$attrib] = $this->$attrib;
        }
        return __CLASS__ . '[' .
                Util::attributesToString($displayAttributes) .']';
    }
}
class_alias('Braintree\Dispute', 'Braintree_Dispute');
