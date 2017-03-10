@mod @mod_socialwiki @test

Feature: Datatables
  Info in tables show up correctly.

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email         |
      | user1    | User      | 1        | u@example.com |
      | user2    | User      | 2        | u@example.com |
      | user3    | User      | 3        | u@example.com |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | C1        | 0        |
    And the following "course enrolments" exist:
      | user  | course | role    |
      | user1 | C1     | student |
      | user2 | C1     | student |
      | user3 | C1     | student |
    And the following "activities" exist:
      | activity   | course | idnumber | name |
      | socialwiki | C1     | sw1      | SW   |

  Scenario: Contributor list
    When I log in as "user1"
    And I follow "Course 1"
    And I follow "SW"
    And I follow "Pages"
    And I press "Make a new Page"
    And I set the field "New page title" to "Test"
    And I press "Create page"
    And I press "Save"
    And I log out

    When I log in as "user2"
    And I follow "Course 1"
    And I follow "SW"
    Then "//table/tbody/tr[1]/td[2]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[1]/td[6]/a[text() = 'User 1']" "xpath_element" should exist
    And I follow "Test"
    And I follow "Edit"
    And I press "Save"
    And I log out

    When I log in as "user3"
    And I follow "Course 1"
    And I follow "SW"
    Then "//table/tbody/tr[1]/td[2]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[1]/td[6]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[2]/a[text() = 'User 2 and one other']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[6]/a[text() = 'User 2']" "xpath_element" should exist
    And I click on "//table/tbody/tr[2]/td[1]/a" "xpath_element"
    And I follow "Edit"
    And I press "Save"
    And I log out

    When I log in as "user2"
    And I follow "Course 1"
    And I follow "SW"
    Then "//table/tbody/tr[1]/td[2]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[1]/td[6]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[2]/a[text() = 'User 2 and one other']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[6]/a[text() = 'User 2']" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[2]/a[text() = 'User 3 and 2 others']" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[6]/a[text() = 'User 3']" "xpath_element" should exist
    And I click on "//table/tbody/tr[3]/td[1]/a" "xpath_element"
    And I click on "#socialwiki-like" "css_element"
    And I log out

    When I log in as "user1"
    And I follow "Course 1"
    And I follow "SW"
    Then "//table/tbody/tr[1]/td[2]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[1]/td[6]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[2]/a[text() = 'User 2 and one other']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[6]/a[not(contains(text(), 'User'))]" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[2]/a[text() = 'User 3 and 2 others']" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[6]/a[text() = 'User 2 and one other']" "xpath_element" should exist
    And I click on "//table/tbody/tr[3]/td[1]/a" "xpath_element"
    And I click on "#socialwiki-like" "css_element"
    And I log out

    When I log in as "user2"
    And I follow "Course 1"
    And I follow "SW"
    Then "//table/tbody/tr[1]/td[2]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[1]/td[6]/a[not(contains(text(), 'User'))]" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[2]/a[text() = 'User 2 and one other']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[6]/a[not(contains(text(), 'User'))]" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[2]/a[text() = 'User 3 and 2 others']" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[6]/a[text() = 'User 1 and 2 others']" "xpath_element" should exist
    And I click on "//table/tbody/tr[3]/td[1]/a" "xpath_element"
    And I follow "Edit"
    And I press "Save"
    And I log out

    When I log in as "user3"
    And I follow "Course 1"
    And I follow "SW"
    Then "//table/tbody/tr[1]/td[2]/a[text() = 'User 1']" "xpath_element" should exist
    Then "//table/tbody/tr[1]/td[6]/a[not(contains(text(), 'User'))]" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[2]/a[text() = 'User 2 and one other']" "xpath_element" should exist
    Then "//table/tbody/tr[2]/td[6]/a[not(contains(text(), 'User'))]" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[2]/a[text() = 'User 3 and 2 others']" "xpath_element" should exist
    Then "//table/tbody/tr[3]/td[6]/a[text() = 'User 1 and one other']" "xpath_element" should exist
    Then "//table/tbody/tr[4]/td[2]/a[text() = 'User 2 and 2 others']" "xpath_element" should exist
    Then "//table/tbody/tr[4]/td[6]/a[text() = 'User 2']" "xpath_element" should exist