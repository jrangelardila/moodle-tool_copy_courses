@tool @tool_copy_courses
Feature: Testing copy_courses in tool_copy_courses
  Background:
    Given the following "categories" exist:
      | name  | category | idnumber |
      | Cat 1 | 0        | CAT1     |
    And the following "courses" exist:
      | fullname | shortname | category |
      | Course 1 | COURSE1   | CAT1     |
      | Course 2 | COURSE2   | 0        |

  @javascript @_file_upload
  Scenario: As admin I can copy courses
    Given I log in as "admin"
    And I navigate to "Courses > Massive Course Copy" in site administration
    Then I should see "Courses"
    And I upload "admin/tool/copy_courses/tests/fixtures/valid_file_example.csv" file to "Upload CSV File" filemanager
    And I press "Submit"
    And I wait "10" seconds
    Then I should see "Validation successful"
    And I should not see "Duplicate shortname"
    And I should not see "Invalid"
