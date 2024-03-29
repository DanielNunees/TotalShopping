<?php
/**
 * 2007-2014 [PagSeguro Internet Ltda.]
 *
 * NOTICE OF LICENSE
 *
 *Licensed under the Apache License, Version 2.0 (the "License");
 *you may not use this file except in compliance with the License.
 *You may obtain a copy of the License at
 *
 *http://www.apache.org/licenses/LICENSE-2.0
 *
 *Unless required by applicable law or agreed to in writing, software
 *distributed under the License is distributed on an "AS IS" BASIS,
 *WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *See the License for the specific language governing permissions and
 *limitations under the License.
 *
 *  @author    PagSeguro Internet Ltda.
 *  @copyright 2007-2014 PagSeguro Internet Ltda.
 *  @license   http://www.apache.org/licenses/LICENSE-2.0
 */
namespace App\Libraries\PagSeguroLibrary\resources;
use App\Libraries\PagSeguroLibrary\PagSeguroLibrary;
use App\Libraries\PagSeguroLibrary\resources\PagSeguroResources;

// Base URLs
$PagSeguroResources['baseUrl'] = array();
$PagSeguroResources['baseUrl']['production'] = "https://pagseguro.uol.com.br";
$PagSeguroResources['baseUrl']['sandbox'] = "https://sandbox.pagseguro.uol.com.br";

// Static URLs
$PagSeguroResources['staticUrl'] = array();
$PagSeguroResources['staticUrl']['production'] = "https://stc.pagseguro.uol.com.br";
$PagSeguroResources['staticUrl']['sandbox'] = "https://stc.sandbox.pagseguro.uol.com.br";

// WebService URLs
$PagSeguroResources['webserviceUrl'] = array();
$PagSeguroResources['webserviceUrl']['production'] = "https://ws.pagseguro.uol.com.br";
$PagSeguroResources['webserviceUrl']['sandbox'] = "https://ws.sandbox.pagseguro.uol.com.br";

// Payment service
$PagSeguroResources['paymentService'] = array();
$PagSeguroResources['paymentService']['servicePath'] = "/v2/checkout";
$PagSeguroResources['paymentService']['checkoutUrl'] = "/v2/checkout/payment.html";
$PagSeguroResources['paymentService']['baseUrl']['production'] = "https://pagseguro.uol.com.br";
$PagSeguroResources['paymentService']['baseUrl']['sandbox'] = "https://sandbox.pagseguro.uol.com.br";
$PagSeguroResources['paymentService']['serviceTimeout'] = 20;

// Session service
$PagSeguroResources['sessionService'] = array();
$PagSeguroResources['sessionService']['url'] = "/sessions";

//Installment service
$PagSeguroResources['installmentService'] = array();
$PagSeguroResources['installmentService']['url'] = "/v2/installments";

// Direct payment service
$PagSeguroResources['directPaymentService'] = array();
$PagSeguroResources['directPaymentService']['servicePath'] = "/v2/transactions";
$PagSeguroResources['directPaymentService']['checkoutUrl'] = "/v2/transactions";
$PagSeguroResources['directPaymentService']['serviceTimeout'] = 20;

// PreApproval service
$PagSeguroResources['preApproval'] = array();
$PagSeguroResources['preApproval']['servicePath'] = "/v2/pre-approvals/request";
$PagSeguroResources['preApproval']['checkoutUrl'] = "/v2/checkout";
$PagSeguroResources['preApproval']['cancelUrl'] = "/v2/pre-approvals/cancel/";
$PagSeguroResources['preApproval']['findUrl'] = "/v2/pre-approvals/";
$PagSeguroResources['preApproval']['paymentUrl'] = "/v2/pre-approvals/payment";
$PagSeguroResources['preApproval']['requestUrl'] = "/v2/pre-approvals/request.html";
$PagSeguroResources['preApproval']['baseUrl']['production'] = $PagSeguroResources['webserviceUrl']['production'];
$PagSeguroResources['preApproval']['baseUrl']['sandbox'] = $PagSeguroResources['webserviceUrl']['sandbox'];
$PagSeguroResources['preApproval']['serviceTimeout'] = 20;

// Notification service
$PagSeguroResources['notificationService'] = array();
$PagSeguroResources['notificationService']['servicePath'] = "/v3/transactions/notifications";
$PagSeguroResources['notificationService']['applicationPath'] = "v2/authorizations/notifications";
$PagSeguroResources['notificationService']['preApprovalPath'] = "v2/pre-approvals/notifications";
$PagSeguroResources['notificationService']['serviceTimeout'] = 20;

// Transaction search service
$PagSeguroResources['transactionSearchService'] = array();
$PagSeguroResources['transactionSearchService']['servicePath']['v2'] = "/v2/transactions";
$PagSeguroResources['transactionSearchService']['servicePath']['v3'] = "/v3/transactions";
$PagSeguroResources['transactionSearchService']['serviceTimeout'] = 20;

// Authorizations service
$PagSeguroResources['authorizationService'] = array();
$PagSeguroResources['authorizationService']['servicePath'] = "/v2/authorizations";
$PagSeguroResources['authorizationService']['approvalUrl'] = "/v2/authorization/request.jhtml";
$PagSeguroResources['authorizationService']['requestUrl'] = "/request";
$PagSeguroResources['authorizationService']['serviceTimeout'] = 20;

// Refund service
$PagSeguroResources['refundService'] = array();
$PagSeguroResources['refundService']['servicePath'] = "/v2/transactions/refunds";
$PagSeguroResources['refundService']['serviceTimeout'] = 200;

// Cancels service
$PagSeguroResources['cancelService'] = array();
$PagSeguroResources['cancelService']['servicePath'] = "/v2/transactions/cancels";
$PagSeguroResources['cancelService']['serviceTimeout'] = 200;
