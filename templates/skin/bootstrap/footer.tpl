                {hook run='content_end'}
            </div> <!-- /content -->


            {if !$noSidebar && $sidebarPosition != 'left'}
                {include file='sidebar.tpl'}
            {/if}
        </div>
    </div> <!-- /wrapper -->


    <footer id="footer">
        <div class="copyright">
        {hook run='copyright'}
    </div>

        {$aLang.foot_develops}

        {hook run='footer_end'}
    </footer>

</div> <!-- /container -->

{include file='toolbar.tpl'}

{hook run='body_end'}

</body>
</html>