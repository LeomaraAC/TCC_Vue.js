<template>
    <nav id="sidebar" :class="{'active': toggled }"> <!-- class active -->
        <div class="sidebar-header text-center">
            <h3><span class="font">SARA</span><small> Campus Capivari</small></h3>
            <strong class="font">S</strong>
        </div>
        <ul class="list-unstyled components">
            <li>
                <a href="/">
                    <i class="fas fa-tachometer-alt"></i>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="#pageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                    <i class="fas fa-university"></i>
                    Administração
                </a>
                <ul class="collapse list-unstyled" id="pageSubmenu">
                    <li>
                        <a href="/master/grupos">Grupos</a>
                    </li>
                </ul>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props:['toggled']
    }
</script>
<style scoped>
    a,
    a:hover,
    a:focus {
        color: inherit;
        text-decoration: none;
        transition: all 0.3s;
    }
    i{
        display: inline-block;
    }
    #sidebar {
        min-width: 250px;
        max-width: 250px;
        background: #179910;
        color: #fff;
        transition: all 0.3s;
    }
    #sidebar.active {
        min-width: 100px;
        max-width: 100px;
        text-align: center;
    }
    .sidebar-header {
        box-shadow: 3px 6px 16px 1px rgba(0,0,0,0.62);
    }
    #sidebar.active .sidebar-header h3 {
        display: none;
    }
    #sidebar.active .sidebar-header strong {
        display: block;
    }
    #sidebar ul li a {
        text-align: left;
    }
    #sidebar.active ul li a {
        padding: 20px 10px;
        text-align: center;
        font-size: 0.85em;
    }
    #sidebar.active ul li a i {
        margin-right: 0;
        display: block;
        font-size: 1.8em;
        margin-bottom: 5px;
    }

    #sidebar.active ul ul a {
        padding: 10px !important;
    }
    #sidebar.active .dropdown-toggle::after {
        top: auto;
        bottom: 10px;
        right: 50%;
        -webkit-transform: translateX(50%);
        -ms-transform: translateX(50%);
        transform: translateX(50%);
    }

    #sidebar .sidebar-header {
        padding: 14.5px;
        background: #169410;
    }
    #sidebar.active .sidebar-header {
        padding: 14px;
        background: #169410;
    }

    #sidebar .sidebar-header strong {
        display: none;
        font-size: 1.8em;
    }

    #sidebar ul.components {
        padding: 20px 0;
        border-bottom: 1px solid #188b11;
    }

    #sidebar ul li a {
        padding: 10px;
        font-size: 1.1em;
        display: block;
    }   

    #sidebar ul li a:hover {
        color: #179910;
        background: #fff;
    }

    #sidebar ul li a i {
        margin-right: 10px;
    }

    #sidebar ul li.active>a,
        a[aria-expanded="true"] {
        color: #fff;
        background: #169410;
    }

    a[data-toggle="collapse"] {
        position: relative;
    }

    .dropdown-toggle::after {
        display: block;
        position: absolute;
        top: 50%;
        right: 20px;
        transform: translateY(-50%);
    }

    ul ul a {
        font-size: 0.9em !important;
        padding-left: 30px !important;
        background: #169410;
    }

    a.download {
        background: #fff;
        color: #179910;
    }

    a.article,
    a.article:hover {
        background: #169410 !important;
        color: #fff !important;
    }
    @media (max-width: 768px) {
  #sidebar {
      min-width: 100px;
      max-width: 100px;
      text-align: center;
      margin-left: -100px !important;
  }
  .dropdown-toggle::after {
      top: auto;
      bottom: 10px;
      right: 50%;
      -webkit-transform: translateX(50%);
      -ms-transform: translateX(50%);
      transform: translateX(50%);
  }
  #sidebar.active {
      margin-left: 0 !important;
  }
  #sidebar .sidebar-header h3 {
      display: none;
  }
  #sidebar .sidebar-header strong {
      display: block;
  }
  #sidebar ul li a {
      padding: 20px 10px;
  }
  #sidebar ul li a span {
      font-size: 0.85em;
  }
  #sidebar ul li a i {
      margin-right: 0;
      display: block;
  }
  #sidebar ul ul a {
      padding: 10px !important;
  }
  #sidebar ul li a i {
      font-size: 1.3em;
  }
  #sidebar {
      margin-left: 0;
  }
  #sidebarCollapse span {
      display: none;
  }
}
</style>

