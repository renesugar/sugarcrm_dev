<?php
/*********************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 ********************************************************************************/


require_once("modules/Calendar/Calendar.php");

class Bug50567Test extends Sugar_PHPUnit_Framework_TestCase
{
    public static function setUpBeforeClass()
    {
        global $current_user;
        $current_user = SugarTestUserUtilities::createAnonymousUser();
    }

    public static function tearDownAfterClass()
    {
        SugarTestUserUtilities::removeAllCreatedAnonymousUsers();
    }

    /**
     * providerCorrectNextMonth
     *
     */
    public function providerCorrectNextMonth()
    {
        return array(
            array('2012-01-31', 'next', '&year=2012&month=2&day=1'),
            array('2012-02-29', 'next', '&year=2012&month=3&day=1'), //Check leap year
            array('2011-02-28', 'next', '&year=2011&month=3&day=1'), //Check non-leap year
            array('2012-12-31', 'next', '&year=2013&month=1&day=1'), //Check new year

            array('2012-01-31', 'previous', '&year=2011&month=12&day=1'),
            array('2012-12-31', 'previous', '&year=2012&month=11&day=1'),
            array('2012-02-29', 'previous', '&year=2012&month=1&day=1'), //Check leap year
            array('2011-02-28', 'previous', '&year=2011&month=1&day=1'), //Check non-leap year
        );
    }


    /**
     * @dataProvider providerCorrectNextMonth
     * @outputBuffering disabled
     */
    public function testCorrectNextMonth($testDate, $direction, $expectedString)
    {
        global $timedate;
        $timedate = TimeDate::getInstance();
        $this->calendar = new Calendar('month');
        $this->calendar->date_time = $timedate->fromString($testDate);
        $uri = $this->calendar->get_neighbor_date_str($direction);
        $this->assertContains($expectedString, $uri, "Failed to get {$direction} expected URL: {$expectedString} from date: {$testDate}");

    }
}