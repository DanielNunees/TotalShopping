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
 * Represents a page of results returned by the search service
 */
namespace App\Libraries\PagSeguroLibrary\domain;
class PagSeguroSearchResult
{

    /***
     * Date/time when this search was executed
     */
    private $date;

    /***
     * Results in the current page
     */
    private $resultsInThisPage;

    /***
     * Total number of pages
     */
    private $totalPages;

    /***
     * Current page.
     */
    private $currentPage;

    /***
     * @return the current page number
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /***
     * Sets the current page number
     * @param integer $currentPage
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
    }

    /***
     * @return the date/time when this search was executed
     */
    public function getDate()
    {
        return $this->date;
    }

    /***
     * Set the date/time when this search was executed
     * @param date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /***
     * @return the number of summaries in the current page
     */
    public function getResultsInThisPage()
    {
        return $this->resultsInThisPage;
    }

    /***
     * Sets the number of summaries in the current page
     *
     * @param resultsInThisPage
     */
    public function setResultsInThisPage($resultsInThisPage)
    {
        $this->resultsInThisPage = $resultsInThisPage;
    }

    /***
     * @return the total number of pages
     */
    public function getTotalPages()
    {
        return $this->totalPages;
    }

    /***
     * Sets the total number of pages
     *
     * @param totalPages
     */
    public function setTotalPages($totalPages)
    {
        $this->totalPages = $totalPages;
    }

    /***
     * @return String a string that represents the current object
     */
    public function toString()
    {
        $result = array();

        $result['Date'] = $this->date;
        $result['CurrentPage'] = $this->currentPage;
        $result['TotalPages'] = $this->totalPages;
        $result['Results in this page'] = $this->resultsInThisPage;

        return "PagSeguroSearchResult: " . var_export($result, true);

    }
}
