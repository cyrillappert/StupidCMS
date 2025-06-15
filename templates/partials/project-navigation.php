<div class="project-nav">
    <button
        class="nav-arrow prev <?= !$nav['hasPrev'] ? 'disabled' : '' ?>"
        hx-get="/work/project?index=<?= $nav['prevIndex'] ?>"
        hx-target="#project-content"
        hx-swap="innerHTML"
        <?= !$nav['hasPrev'] ? 'disabled' : '' ?>>
        Previous
    </button>

    <button
        class="nav-arrow next <?= !$nav['hasNext'] ? 'disabled' : '' ?>"
        hx-get="/work/project?index=<?= $nav['nextIndex'] ?>"
        hx-target="#project-content"
        hx-swap="innerHTML"
        <?= !$nav['hasNext'] ? 'disabled' : '' ?>>
        Next
    </button>
</div>