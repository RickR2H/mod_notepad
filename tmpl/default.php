<?php
/**
 * @copyright	Copyright (c) 2021 R2H (https://www.r2h.nl). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

HTMLHelper::_('bootstrap.offcanvas');
HTMLHelper::_('bootstrap.tab');

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

// Load CSS
$wa->registerAndUseStyle('notepad-default.css', 'mod_notepad/notepad-default.css', [], ['as'=>'style']);

$offcanvasElement = 'offcanvas' . $module->id;

/* INLINE CSS */
/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();

$style = <<<CSS
 .offcanvas, .offcanvas-lg, .offcanvas-md, .offcanvas-sm, .offcanvas-xl, .offcanvas-xxl {
    --offcanvas-width: 600px;
    --offcanvas-box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,.075);
	overflow-y: auto;
}
.notepad-button {
    -webkit-box-align: center;
    -ms-flex-align: center;
    -webkit-padding-end: 4px;
    align-items: center;
    background-color: var(--template-bg-dark-60);
    border-radius: 22px;
    color: #fff;
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    line-height: 1rem;
    padding-inline-end: 4px;
    border: 0;
    z-index: 1039;
    position: relative;
    padding: 0;
}

.notepad-button:hover {
    color: #fff;
    background-color: var(--template-bg-dark-50);
}

.offcanvas-title {
    margin-bottom: 0;
    line-height: 1.75;
}

.offcanvas-body {
    flex-grow: 1;
    padding: 1rem 1rem;
    overflow-y: auto;
}
CSS;
$wa->addInlineStyle($style, ['name' => 'module' . $module->id]);

$script = <<<SCRIPT
window.addEventListener("load", () => {
	let drawer = document.getElementById("$offcanvasElement")
	document.body.insertBefore(drawer, document.body.firstChild);
})
SCRIPT;
$wa->addInlineScript($script, ['name' => 'module' . $module->id]);
?>

<a class="d-inline-flex notepad-button" data-bs-toggle="offcanvas" href="#offcanvas<?php echo $module->id; ?>" role="button" aria-controls="offcanvas<?php echo $module->id; ?>">
	<div class="header-item-icon">
        <span class="icon-paragraph-left" aria-hidden="true"></span>
    </div>
	<div class="header-item-text"><?php echo $params->get('btntitle', 'Notes <i class="fas fa-arrow-circle-right"></i>'); ?></div>
</a>

<div class="offcanvas offcanvas-notes <?php echo $params->get('direction','offcanvas-start'); ?>" tabindex="-1" id="offcanvas<?php echo $module->id; ?>" aria-labelledby="offcanvas<?php echo $module->id; ?>Label">
	<form method="POST">
		<div class="offcanvas-header">
			<h5 class="offcanvas-title" id="offcanvas<?php echo $module->id; ?>Label"><?php echo $module->title; ?></h5>
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
		</div>
		<div class="offcanvas-body">
			<ul class="nav nav-tabs" id="notesTab" role="tablist">
				<li class="nav-item" role="presentation">
					<button class="nav-link active" id="note-tab" data-bs-toggle="tab"
						data-bs-target="#note-tab-pane<?php echo $module->id; ?>" type="button" role="tab" aria-controls="note-tab-pane<?php echo $module->id; ?>"
						aria-selected="true"><?php echo Text::_('MOD_NOTEPAD_NOTESTAB'); ?></button>
				</li>
				<?php if ($canEdit === true) : ?>
				<li class="nav-item" role="presentation">
					<button class="nav-link" id="editnote-tab" data-bs-toggle="tab"
						data-bs-target="#editnote-tab-pane<?php echo $module->id; ?>" type="button" role="tab"
						aria-controls="editnote-tab-pane<?php echo $module->id; ?>" aria-selected="false"><?php echo Text::_('MOD_NOTEPAD_EDITTAB'); ?></button>
				</li>
				<?php endif; ?>
			</ul>
			<div class="tab-content" id="myTabContent">
				<div class="tab-pane fade show active" id="note-tab-pane<?php echo $module->id; ?>" role="tabpanel" aria-labelledby="note-tab" tabindex="0">
					<div class="bg-light mt-3 p-3">
						<?php if ($fileContent) : ?>
							<?php echo $Parsedown->text($fileContent); ?>
						<?php else : ?>
							<div class="fst-italic small"><?php echo Text::_('MOD_NOTEPAD_NONOTES'); ?></div>
						<?php endif; ?>
					</div>
					<a class="d-block text-center mt-3 small" href="<?php echo $downloadPath; ?>" download><?php echo Text::_('MOD_NOTEPAD_DOWNLOAD'); ?></a>
				</div>
				<?php if ($canEdit === true) : ?>
				<div class="tab-pane fade" id="editnote-tab-pane<?php echo $module->id; ?>" role="tabpanel" aria-labelledby="editnote-tab" tabindex="0">
					<div class="form-body">
						<div class="form-body-element my-3">
							<label for="editNotesFormControlTextarea" class="form-label"><?php echo Text::_('MOD_NOTEPAD_MDEDITOR'); ?></label>
							<textarea name="text" class="form-control font-monospace" id="editNotesFormControlTextarea" rows="50"><?php echo $fileContent; ?></textarea>
						</div>
					</div>
					<div class="form-footer text-end">
						<input type="submit" value="<?php echo Text::_('MOD_NOTEPAD_SAVECLOSE'); ?>" class="btn btn-primary" />
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</form>
</div>
