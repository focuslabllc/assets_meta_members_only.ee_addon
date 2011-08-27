<?php if ( ! defined('APP_VER')) exit('No direct script access allowed');

/**
 * Assets Meta: Members Only
 *
 * Adds a new meta field to the Assets HUD for "Members Only"
 *
 * @package    Assets Meta: Members Only
 * @author     Focus Lab, LLC <dev@focuslabllc.com>
 * @copyright  Copyright (c) 2011 Focus Lab, LLC
 * @link       https://github.com/focuslabllc/assets_meta_members_only.ee_addon
 * @license    MIT  http://opensource.org/licenses/mit-license.php
 */

class Assets_meta_members_only_ext {
	
	/**
	 * @var string  Extension name
	 */
	public $name = 'Assets Meta: Members Only';
	
	
	/**
	 * @var string  Extension version number
	 */
	public $version = '1.0.0';
	
	
	/**
	 * @var string  Extension description
	 */
	public $description = 'Adds a “Members Only?” field to Assets metadata';
	
	
	/**
	 * @var string  Do Settings exist? (y|n)
	 */
	public $settings_exist = 'n';
	
	
	/**
	 * @var string  Extensions documentation URL
	 */
	public $docs_url = 'https://github.com/focuslabllc/assets_meta_members_only.ee_addon';
	
	
	
	
	/**
	 * Constructor
	 *
	 * @access    public
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    void
	 */
	public function __construct()
	{
		// Make a local reference to the ExpressionEngine super object
		$this->EE =& get_instance();
	}
	// End function __construct()
	
	
	
	
	/**
	 * Add our new meta data row
	 * 
	 * @access    public
	 * @param     obj         assets file object
	 * @author    Erik Reagan <erik@focuslabllc.com>
	 * @return    string      new HTML for metadata view
	 */
	public function assets_file_meta_add_row($file)
	{
		$r = ($this->EE->extensions->last_call !== FALSE) ? $this->EE->extensions->last_call : '';
		
		$yes = ($file->row('members_only') == 'y') ? 'selected="selected"' : '' ;
		$no  = ( ! $file->row('members_only') OR $file->row('members_only') == 'n') ? 'selected="selected"' : '' ;
		
		$r .= '<tr>'
		    .   '<th scope="row">Members&nbsp;Only?</th>'
		    .   '<td>'
		    .      '<select name="members_only">'
		    .        '<option ' . $no . 'value="n">No</option>'
		    .        '<option ' . $yes . 'value="y">Yes</option>'
		    .      '</select>'
		    .   '</td>'
		    . '</tr>';
		    
		return $r;
	}
	// End function assets_file_meta_add_row()
	
	
	
	
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @access    public
	 * @return    void
	 */
	public function activate_extension()
	{
		$this->EE->db->insert('extensions', array(
			'class'    => __CLASS__,
			'method'   => 'assets_file_meta_add_row',
			'hook'     => 'assets_file_meta_add_row',
			'settings' => '',
			'priority' => 10,
			'version'  => $this->version,
			'enabled'  => 'y'
		));
		
		// Check for our column and add it to exp_assets if it doesn't exist yet
		if ($this->EE->db->table_exists('assets') && ! $this->EE->db->field_exists('members_only', 'assets'))
		{
			$this->EE->load->dbforge();
			$this->EE->dbforge->add_column('assets', array(
				// Default to assets not being members only
				'members_only' => array('type' => 'varchar', 'constraint' => 1, 'default' => 'n'),
			));
		}
	}
	// End function activate_extension()
	
	
	
	
	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 * and deletes the custom column from the exp_assets table
	 *
	 * @access    public
	 * @return    void
	 */
	function disable_extension()
	{
		$this->EE->db->where('class', __CLASS__)
		             ->delete('extensions');
		// Drop the custom column from exp_assets
		if ($this->EE->db->table_exists('assets') && $this->EE->db->field_exists('members_only', 'assets'))
		{
			$this->EE->load->dbforge();
			$this->EE->dbforge->drop_column('assets', 'members_only');
		}
	}
	// End function disable_extension()
	
}
// End class Assets_meta_members_only_ext

// End of file ext.assets_meta_members_only.php */
// Location: ./system/expressionengine/third_party/assets_meta_members_only/ext.assets_meta_members_only.php