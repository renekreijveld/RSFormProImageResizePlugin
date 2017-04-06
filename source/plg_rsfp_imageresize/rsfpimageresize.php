<?php
/**
 * @package          Joomla.Plugin
 * @subpackage       System.RSFPImageResize
 * @copyright    (C) 2017 www.renekreijveld.nl
 * @license          GNU Public License version 3 or later
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// load Eventviva ImageResize
require __DIR__ . '/vendor/autoload.php';
use \Eventviva\ImageResize;

class plgSystemRSFPImageResize extends JPlugin
{

	public function __construct(&$subject, $config)
	{
		parent::__construct($subject, $config);
	}

	public function rsfp_bk_onAfterShowConfigurationTabs($tabs)
	{

		JFactory::getLanguage()->load('plg_system_rsfpimageresize');

		$tabs->addTitle(JText::_('RSFP_IMAGERESIZE_LABEL'), 'form-imageresize');
		$tabs->addContent($this->imageresizeConfigurationScreen());
	}

	public function rsfp_f_onAfterFileUpload($params)
	{

		// Get plugin settings
		$imgresize_log = RSFormProHelper::getConfig('imgresize_log');
		$do_tn         = RSFormProHelper::getConfig('imgresize_do_tn');
		$tn_width      = RSFormProHelper::getConfig('imgresize_tn_width');
		$tn_height     = RSFormProHelper::getConfig('imgresize_tn_height');
		$do_xs         = RSFormProHelper::getConfig('imgresize_do_xs');
		$xs_width      = RSFormProHelper::getConfig('imgresize_xs_width');
		$xs_height     = RSFormProHelper::getConfig('imgresize_xs_height');
		$do_sm         = RSFormProHelper::getConfig('imgresize_do_sm');
		$sm_width      = RSFormProHelper::getConfig('imgresize_sm_width');
		$sm_height     = RSFormProHelper::getConfig('imgresize_sm_height');
		$do_md         = RSFormProHelper::getConfig('imgresize_do_md');
		$md_width      = RSFormProHelper::getConfig('imgresize_md_width');
		$md_height     = RSFormProHelper::getConfig('imgresize_md_height');
		$do_lg         = RSFormProHelper::getConfig('imgresize_do_lg');
		$lg_width      = RSFormProHelper::getConfig('imgresize_lg_width');
		$lg_height     = RSFormProHelper::getConfig('imgresize_lg_height');

		// Initialize logging
		if ($imgresize_log == 1)
		{
			jimport('joomla.log.log');
			JLog::addLogger(array('text_file' => 'imageupload.' . date('Y-m-d') . '.log.php'), JLog::ALL, array('imageupload'));
			JLog::add(JText::_('Start RSFP Image Resize'), JLog::INFO, 'imageupload');
		}

		// Get file upload values
		$formId    = $params['formId'];
		$fieldname = $params['fieldname'];
		$file      = $params['file'];
		$name      = $params['name'];

		$upload     = pathinfo($file);
		$image      = new ImageResize($file);
		$org_width  = $image->getSourceWidth();
		$org_height = $image->getSourceHeight();

		if ($imgresize_log == 1) JLog::add(JText::_('Start processing file ' . $name . ' ' . $org_width . 'x' . $org_height), JLog::INFO, 'imageupload');

		// Thumbnail
		if (($tn_width > 0 || $tn_height > 0) && $do_tn == 1)
		{
			$image->resizeToBestFit($tn_width, $tn_height);
			$dst_width  = floor($image->getDestWidth());
			$dst_height = floor($image->getDestHeight());
			$image->save($upload["dirname"] . '/' . $upload["filename"] . '-tn.' . $upload["extension"]);
			if ($imgresize_log == 1) JLog::add(JText::_('Saved thumbnail version ' . $upload["filename"] . '-tn.' . $upload["extension"] . ' ' . $dst_width . 'x' . $dst_height), JLog::INFO, 'imageupload');
		}

		// Extra small
		if (($xs_width > 0 || $xs_height > 0) && $do_xs == 1)
		{
			$image->resizeToBestFit($xs_width, $xs_height);
			$dst_width  = floor($image->getDestWidth());
			$dst_height = floor($image->getDestHeight());
			$image->save($upload["dirname"] . '/' . $upload["filename"] . '-xs.' . $upload["extension"]);
			if ($imgresize_log == 1) JLog::add(JText::_('Saved extra small version ' . $upload["filename"] . '-xs.' . $upload["extension"] . ' ' . $dst_width . 'x' . $dst_height), JLog::INFO, 'imageupload');
		}

		// Small
		if (($sm_width > 0 || $sm_height > 0) && $do_sm == 1)
		{
			$image->resizeToBestFit($sm_width, $sm_height);
			$dst_width  = floor($image->getDestWidth());
			$dst_height = floor($image->getDestHeight());
			$image->save($upload["dirname"] . '/' . $upload["filename"] . '-sm.' . $upload["extension"]);
			if ($imgresize_log == 1) JLog::add(JText::_('Saved small version ' . $upload["filename"] . '-sm.' . $upload["extension"] . ' ' . $dst_width . 'x' . $dst_height), JLog::INFO, 'imageupload');
		}

		// Medium
		if (($md_width > 0 || $md_height > 0) && $do_md == 1)
		{
			$image->resizeToBestFit($md_width, $md_height);
			$dst_width  = floor($image->getDestWidth());
			$dst_height = floor($image->getDestHeight());
			$image->save($upload["dirname"] . '/' . $upload["filename"] . '-md.' . $upload["extension"]);
			if ($imgresize_log == 1) JLog::add(JText::_('Saved medium version ' . $upload["filename"] . '-md.' . $upload["extension"] . ' ' . $dst_width . 'x' . $dst_height), JLog::INFO, 'imageupload');
		}

		// Large
		if (($lg_width > 0 || $lg_height > 0) && $do_lg == 1)
		{
			$image->resizeToBestFit($lg_width, $lg_height);
			$dst_width  = floor($image->getDestWidth());
			$dst_height = floor($image->getDestHeight());
			$image->save($upload["dirname"] . '/' . $upload["filename"] . '-lg.' . $upload["extension"]);
			if ($imgresize_log == 1) JLog::add(JText::_('Saved large version ' . $upload["filename"] . '-lg.' . $upload["extension"] . ' ' . $dst_width . 'x' . $dst_height), JLog::INFO, 'imageupload');
		}

		if ($imgresize_log == 1) JLog::add(JText::_('End RSFP Image Resize'), JLog::INFO, 'imageupload');

		return;

	}

	public function imageresizeConfigurationScreen()
	{

		ob_start();
		$imgresize_log = RSFormProHelper::getConfig('imgresize_log');
		$do_tn         = RSFormProHelper::getConfig('imgresize_do_tn');
		$tn_width      = RSFormProHelper::getConfig('imgresize_tn_width');
		$tn_height     = RSFormProHelper::getConfig('imgresize_tn_height');
		$do_xs         = RSFormProHelper::getConfig('imgresize_do_xs');
		$xs_width      = RSFormProHelper::getConfig('imgresize_xs_width');
		$xs_height     = RSFormProHelper::getConfig('imgresize_xs_height');
		$do_sm         = RSFormProHelper::getConfig('imgresize_do_sm');
		$sm_width      = RSFormProHelper::getConfig('imgresize_sm_width');
		$sm_height     = RSFormProHelper::getConfig('imgresize_sm_height');
		$do_md         = RSFormProHelper::getConfig('imgresize_do_md');
		$md_width      = RSFormProHelper::getConfig('imgresize_md_width');
		$md_height     = RSFormProHelper::getConfig('imgresize_md_height');
		$do_lg         = RSFormProHelper::getConfig('imgresize_do_lg');
		$lg_width      = RSFormProHelper::getConfig('imgresize_lg_width');
		$lg_height     = RSFormProHelper::getConfig('imgresize_lg_height'); ?>
        <div id="page-imageresize">
            <fieldset class="adminform form-horizontal">
                <h3><?php echo JText::_('RSFP_IMAGERESIZE_LOGGING'); ?></h3>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_log-lbl" for="rsformConfig_imgresize_log">
							<?php echo JText::_('RSFP_IMAGERESIZE_LOG_LABEL'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <fieldset id="rsformConfig_imgresize_log" class="btn-group radio">
                            <input type="radio" id="rsformConfig_imgresize_log1" name="rsformConfig[imgresize_log]"
                                   value="1" <?php if ($imgresize_log == 1) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_log1">
								<?php echo JText::_('JYES'); ?>
                            </label>
                            <input type="radio" id="rsformConfig_imgresize_log0" name="rsformConfig[imgresize_log]"
                                   value="0" <?php if ($imgresize_log == 0) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_log0">
								<?php echo JText::_('JNO'); ?>
                            </label>
                        </fieldset>
                    </div>
                </div>
                <h3><?php echo JText::_('RSFP_IMAGERESIZE_THUMBNAIL'); ?></h3>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_do_tn-lbl" for="rsformConfig_imgresize_do_tn">
							<?php echo JText::_('RSFP_IMAGERESIZE_DO_TN_LABEL'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <fieldset id="rsformConfig_imgresize_do_tn" class="btn-group radio">
                            <input type="radio" id="rsformConfig_imgresize_do_tn1" name="rsformConfig[imgresize_do_tn]"
                                   value="1" <?php if ($do_tn == 1) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_tn1">
								<?php echo JText::_('JYES'); ?>
                            </label>
                            <input type="radio" id="rsformConfig_imgresize_do_tn0" name="rsformConfig[imgresize_do_tn]"
                                   value="0" <?php if ($do_tn == 0) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_tn0">
								<?php echo JText::_('JNO'); ?>
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_tn_width-lbl" for="rsformConfig_imgresize_tn_width">
							<?php echo JText::_('RSFP_IMAGERESIZE_WIDTH'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_tn_width]"
                               id="rsformConfig_imgresize_tn_width" value="<?php echo $tn_width; ?>"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_tn_height-lbl" for="rsformConfig_imgresize_tn_height">
							<?php echo JText::_('RSFP_IMAGERESIZE_HEIGHT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_tn_height]"
                               id="rsformConfig_imgresize_tn_height" value="<?php echo $tn_height; ?>"/>
                    </div>
                </div>
                <h3><?php echo JText::_('RSFP_IMAGERESIZE_XS'); ?></h3>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_do_xs-lbl" for="rsformConfig_imgresize_do_xs">
							<?php echo JText::_('RSFP_IMAGERESIZE_DO_XS_LABEL'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <fieldset id="rsformConfig_imgresize_do_xs" class="btn-group radio">
                            <input type="radio" id="rsformConfig_imgresize_do_xs1" name="rsformConfig[imgresize_do_xs]"
                                   value="1" <?php if ($do_xs == 1) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_xs1">
								<?php echo JText::_('JYES'); ?>
                            </label>
                            <input type="radio" id="rsformConfig_imgresize_do_xs0" name="rsformConfig[imgresize_do_xs]"
                                   value="0" <?php if ($do_xs == 0) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_xs0">
								<?php echo JText::_('JNO'); ?>
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_xs_width-lbl" for="rsformConfig_imgresize_xs_width">
							<?php echo JText::_('RSFP_IMAGERESIZE_WIDTH'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_xs_width]"
                               id="rsformConfig_imgresize_xs_width" value="<?php echo $xs_width; ?>"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_xs_height-lbl" for="rsformConfig_imgresize_xs_height">
							<?php echo JText::_('RSFP_IMAGERESIZE_HEIGHT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_xs_height]"
                               id="rsformConfig_imgresize_xs_height" value="<?php echo $xs_height; ?>"/>
                    </div>
                </div>
                <h3><?php echo JText::_('RSFP_IMAGERESIZE_SM'); ?></h3>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_do_sm-lbl" for="rsformConfig_imgresize_do_sm">
							<?php echo JText::_('RSFP_IMAGERESIZE_DO_SM_LABEL'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <fieldset id="rsformConfig_imgresize_do_sm" class="btn-group radio">
                            <input type="radio" id="rsformConfig_imgresize_do_sm1" name="rsformConfig[imgresize_do_sm]"
                                   value="1" <?php if ($do_sm == 1) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_sm1">
								<?php echo JText::_('JYES'); ?>
                            </label>
                            <input type="radio" id="rsformConfig_imgresize_do_sm0" name="rsformConfig[imgresize_do_sm]"
                                   value="0" <?php if ($do_sm == 0) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_sm0">
								<?php echo JText::_('JNO'); ?>
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_sm_width-lbl" for="rsformConfig_imgresize_sm_width">
							<?php echo JText::_('RSFP_IMAGERESIZE_WIDTH'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_sm_width]"
                               id="rsformConfig_imgresize_sm_width" value="<?php echo $sm_width; ?>"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_sm_height-lbl" for="rsformConfig_imgresize_sm_height">
							<?php echo JText::_('RSFP_IMAGERESIZE_HEIGHT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_sm_height]"
                               id="rsformConfig_imgresize_sm_height" value="<?php echo $sm_height; ?>"/>
                    </div>
                </div>
                <h3><?php echo JText::_('RSFP_IMAGERESIZE_MD'); ?></h3>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_do_md-lbl" for="rsformConfig_imgresize_do_md">
							<?php echo JText::_('RSFP_IMAGERESIZE_DO_MD_LABEL'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <fieldset id="rsformConfig_imgresize_do_md" class="btn-group radio">
                            <input type="radio" id="rsformConfig_imgresize_do_md1" name="rsformConfig[imgresize_do_md]"
                                   value="1" <?php if ($do_md == 1) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_md1">
								<?php echo JText::_('JYES'); ?>
                            </label>
                            <input type="radio" id="rsformConfig_imgresize_do_md0" name="rsformConfig[imgresize_do_md]"
                                   value="0" <?php if ($do_md == 0) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_md0">
								<?php echo JText::_('JNO'); ?>
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_md_width-lbl" for="rsformConfig_imgresize_md_width">
							<?php echo JText::_('RSFP_IMAGERESIZE_WIDTH'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_md_width]"
                               id="rsformConfig_imgresize_md_width" value="<?php echo $md_width; ?>"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_md_height-lbl" for="rsformConfig_imgresize_md_height">
							<?php echo JText::_('RSFP_IMAGERESIZE_HEIGHT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_md_height]"
                               id="rsformConfig_imgresize_md_height" value="<?php echo $md_height; ?>"/>
                    </div>
                </div>
                <h3><?php echo JText::_('RSFP_IMAGERESIZE_LG'); ?></h3>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_do_lg-lbl" for="rsformConfig_imgresize_do_lg">
							<?php echo JText::_('RSFP_IMAGERESIZE_DO_LG_LABEL'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <fieldset id="rsformConfig_imgresize_do_lg" class="btn-group radio">
                            <input type="radio" id="rsformConfig_imgresize_do_lg1" name="rsformConfig[imgresize_do_lg]"
                                   value="1" <?php if ($do_lg == 1) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_lg1">
								<?php echo JText::_('JYES'); ?>
                            </label>
                            <input type="radio" id="rsformConfig_imgresize_do_lg0" name="rsformConfig[imgresize_do_lg]"
                                   value="0" <?php if ($do_lg == 0) echo 'checked="checked"'; ?> />
                            <label for="rsformConfig_imgresize_do_lg0">
								<?php echo JText::_('JNO'); ?>
                            </label>
                        </fieldset>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_lg_width-lbl" for="rsformConfig_imgresize_lg_width">
							<?php echo JText::_('RSFP_IMAGERESIZE_WIDTH'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_lg_width]"
                               id="rsformConfig_imgresize_lg_width" value="<?php echo $lg_width; ?>"/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="control-label">
                        <label id="rsformConfig_imgresize_lg_height-lbl" for="rsformConfig_imgresize_lg_height">
							<?php echo JText::_('RSFP_IMAGERESIZE_HEIGHT'); ?>
                        </label>
                    </div>
                    <div class="controls">
                        <input class="input-small" type="text" name="rsformConfig[imgresize_lg_height]"
                               id="rsformConfig_imgresize_lg_height" value="<?php echo $lg_height; ?>"/>
                    </div>
                </div>
            </fieldset>
        </div>
		<?php
		$contents = ob_get_contents();
		ob_end_clean();

		return $contents;
	}
}