<?php
session_start();
include_once "menu.php";
?>
<script type="text/javascript" src="lib/dashboard/js/lib/jquery.dashboard.min.js"></script>

<script type="text/javascript">
    // This is the code for definining the dashboard
    $(document).ready(function () {

        // Tabs 
        $('#tabs').tabs();


        // load the templates
        $('body').append('<div id="templates"></div>');
        $("#templates").hide();
        $("#templates").load("lib/dashboard/templates.html", initDashboard);



        function initDashboard() {

            // to make it possible to add widgets more than once, we create clientside unique id's
            // this is for demo purposes: normally this would be an id generated serverside
            var startId = <?php print $_SESSION['userID']; ?>;

            var dashboard = $('#dashboard').dashboard({
                // layout class is used to make it possible to switch layouts
                layoutClass: 'layout',
                // feed for the widgets which are on the dashboard when opened
                json_data: {
                    url: "lib/dashboard/jsonfeed/startupwidgets.php"
                },

                // stateChangeUrl : "lib/dashboard/savemydashoard.php",

                // json feed; the widgets which you can add to your dashboard
                addWidgetSettings: {
                    widgetDirectoryUrl: "lib/dashboard/jsonfeed/widgetcategories.json"
                },

                // Definition of the layout
                // When using the layoutClass, it is possible to change layout using only another class. In this case
                // you don't need the html property in the layout

                layouts:
                        [
                            {title: "Layout1",
                                id: "layout1",
                                image: "lib/dashboard/layouts/layout1.png",
                                html: '<div class="layout layout-a"><div class="column first column-first"></div></div>',
                                classname: 'layout-a'
                            },
                            {title: "Layout2",
                                id: "layout2",
                                image: "lib/dashboard/layouts/layout2.png",
                                html: '<div class="layout layout-aa"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                                classname: 'layout-aa'
                            },
                            {title: "Layout3",
                                id: "layout3",
                                image: "lib/dashboard/layouts/layout3.png",
                                html: '<div class="layout layout-ba"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                                classname: 'layout-ba'
                            },
                            {title: "Layout4",
                                id: "layout4",
                                image: "lib/dashboard/layouts/layout4.png",
                                html: '<div class="layout layout-ab"><div class="column first column-first"></div><div class="column second column-second"></div></div>',
                                classname: 'layout-ab'
                            },
                            {title: "Layout5",
                                id: "layout5",
                                image: "lib/dashboard/layouts/layout5.png",
                                html: '<div class="layout layout-aaa"><div class="column first column-first"></div><div class="column second column-second"></div><div class="column third column-third"></div></div>',
                                classname: 'layout-aaa'
                            }
                        ]

            }); // end dashboard call

            // binding for a widgets is added to the dashboard
            dashboard.element.live('dashboardAddWidget', function (e, obj) {
                var widget = obj.widget;

                dashboard.addWidget({
                    "id": startId++,
                    "title": widget.title,
                    "url": widget.url,
                    "metadata": widget.metadata
                }, dashboard.element.find('.column:first'));
            });

            // the init builds the dashboard. This makes it possible to first unbind events before the dashboars is built.
            dashboard.init();
        }
    });

</script>


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php print "SteviaCRM - " . $_SESSION['companyName']; ?></title>

<link rel="stylesheet" type="text/css" href="lib/dashboard/themes/default/dashboardui.css" />


<link rel="stylesheet" type="text/css" href="lib/jquery/jquery.jqplot.css" />  
<script class="code" type="text/javascript">
    var addProduct = "<?php print $LANG['addProduct']; ?>"
    var editProduct = "<?php print $LANG['editProduct']; ?>"

    var addUser = "<?php print $LANG['add_user']; ?>"
    var editUser = "<?php print $LANG['edit_user']; ?>"

    var addRole = "<?php print $LANG['add_role']; ?>"
    var editRole = "<?php print $LANG['edit_role']; ?>"

    var addCurrencie = "<?php print $LANG['add_currency']; ?>"
    var editCurrencie = "<?php print $LANG['edit_currency']; ?>"

    var addContract = "<?php print $LANG['add_contract']; ?>"
    var editContract = "<?php print $LANG['edit_contract']; ?>"

    var addOrderStatu = "<?php print $LANG['add_order_status']; ?>"
    var editOrderStatu = "<?php print $LANG['edit_order_status']; ?>"

    var addWorkplace = "<?php print $LANG['add_workplace']; ?>"
    var editWorkplace = "<?php print $LANG['edit_workplace']; ?>"

    var addDepartment = "<?php print $LANG['add_department']; ?>"
    var editDepartment = "<?php print $LANG['edit_department']; ?>"

    var addCallingList = "<?php print $LANG['add_calling_list']; ?>"
    var editCallingList = "<?php print $LANG['edit_calling_list']; ?>"

    var dataSaved = "<?php print $LANG['data_saved']; ?>"


</script>

</head>

<body style="font-size:12px;margin:0px;padding:2px">
    <div id="main_table" style="margin-top:0px;padding:0px;">
        <!--  Show Tabs -->   
        <div id="tabs" style="width:100%;float:left;margin-top:0px">
            <!--  Show Tabs Heading --> 
            <ul>
                <li><a href="#mydashboard"><?php print $LANG['my_dashboard']; ?></a></li>
                <li><a href="#reports"><?php print $LANG['my_reports']; ?></a></li>			
                <li><a href="#settings"><?php print $LANG['my_settings']; ?></a></li>
            </ul>
            <!--  End Tabs Heading --> 			


            <!--  Show Dashboard Module  -->
            <div id="mydashboard" style="padding:0;margin:0;">
                <div>
                    <div class="headerlinks">
                        <a class="openaddwidgetdialog headerlink" href="#">Legg til innstikk</a>&nbsp;<span class="headerlink">|</span>&nbsp;
                        <a class="editlayout headerlink" href="#">Rediger layout</a>
                    </div>
                </div>


                <div id="dashboard" class="dashboard">
                    <!-- this HTML covers all layouts. The 5 different layouts are handled by setting another layout classname -->
                    <div class="layout">
                        <div class="column first column-first"></div>
                        <div class="column second column-second"></div>
                        <div class="column third column-third"></div>
                    </div>
                </div>



            </div>
            <!--  End Dashboard Module  -->

            <!--  Show Reports Module  -->
            <div id="reports">
                <?php include_once("modules/reports/reports_index.php"); ?>
            </div>
            <!--  End Reports Module  -->

            <!--  Show Settings Module  -->
            <div id="settings">
                <?php include_once("modules/admin/my_settings.php"); ?>
            </div>
            <!--  End Settings Module  -->


        </div>
        <!--  End tabs -->
        <br>

    </div>
    <!--  End main_table div -->



</body>
</html>
