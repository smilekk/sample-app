<?php defined('SYSPATH') or die('No direct script access.');

/**
 * A service class to manage tokens
 */
class Service_Token extends Service
{

	/**
	 * Create a token for a given loaded mongo model
	 *
	 * @param {Model_Mongo} $model
	 * @param {boolean} $is_permanent
	 * @return {Model_App_Token}
	 */
	public function create ( $model, $is_permanent = false )
	{
		// Create a new empty token
		$token = Model::factory('App_Token');

		// Build data
		$data = array(
			'is_permanent' => $is_permanent,
			'target_id' => $model->id(),
			'target_type' => get_class($model)
		);

		// Save data
		$token->set_data($data)->save();

		return $token;
	}

	/**
	 * Get a token from its id
	 *
	 * @param {string} $id
	 * @return {Model_App_Token}
	 */
	public function get ( $id )
	{
		// Create a new empty token
		$token = Model::factory('App_Token');

		// Load the token
		$token->load($id);

		// Raise an exception if account could not be loaded
		if (!$token->loaded())
			throw Service_Exception::factory('NotFound', 'Token not found')->data(array('id' => $id));

		return $token;
	}

	/**
	 * Get a all tokens for a given mongo model
	 *
	 * @param {App_Model_Mongo} $model
	 * @param {boolean} $is_permanent Only permanent tokens
	 * @return {array}
	 */
	public function get_all ( $model, $is_permanent = false )
	{
		if (!$model->loaded())
			return array();

		return Model::factory('App_Token')->search_by_target($model->id(), $is_permanent);
	}

	/**
	 * Delete a token
	 *
	 * @param {Model_App_Token}
	 * @return {boolean}
	 */
	public function remove ( $token )
	{
		if (!$token->remove())
			throw Service_Exception::factory('UnknownError', $token->last_error());

		return TRUE;
	}

}

