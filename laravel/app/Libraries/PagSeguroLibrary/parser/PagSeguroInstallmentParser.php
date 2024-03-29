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

/***
 * Class PagSeguroInstallmentParser
 */
namespace App\Libraries\PagSeguroLibrary\parser;
class PagSeguroInstallmentParser extends PagSeguroServiceParser
{
    /***
     * @param $str_xml
     * @return PagSeguroInstallment
     */
    public static function readInstallments($str_xml)
    {

        // Parser
        $parser = new PagSeguroXmlParser($str_xml);
        
        
        // <installment>
        $data = $parser->getResult('installments');
        
        
        // <installment><installments>
        if (isset($data)) {
            if (isset($data["installment"][0])) {
                foreach ($data["installment"] as $installment) {
                    $installments[] = new PagSeguroInstallment(
                        $installment['cardBrand'],
                        $installment['quantity'],
                        $installment['amount'],
                        $installment['totalAmount'],
                        $installment['interestFree']
                    );
                }
            } else {
                    $installments[] = new PagSeguroInstallment(
                        $data["installment"]['cardBrand'],
                        $data["installment"]['quantity'],
                        $data["installment"]['amount'],
                        $data["installment"]['totalAmount'],
                        $data["installment"]['interestFree']
                    );
            }
            $installments = new PagSeguroInstallments($installments);
            return $installments;
        }
    }
}
