Feature: Task
    Permet de vérifier que mes tasks fonctionnent

Scenario: Multiples task are added to the basket
    Given An empty task
    And A task costing 5 $ is added to the basket
    Then the task price is 5 $