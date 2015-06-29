<?php

class ChecklistTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testCreateChecklist()
	{
    $data_array = array(
      'location_id' => 2, 
      'user_id' => 2, 
      'data' => array(
        array(
          'subcategory_id' => 1,
          'grade' => 'D'
        ),
        array(
          'subcategory_id' => 2,
          'grade' => 'A'
        )
      )
    );
		$response = $this->call(
      'POST', 
      '/checklist', 
      [], 
      [], 
      [], 
      ['CONTENT_TYPE' => 'application/json'],
      json_encode($data_array)
    );

		$this->assertEquals(200, $response->getStatusCode());
	}

}
