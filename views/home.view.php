<?php
require_once "views/base.view.php";

  class HomeView extends BaseView {

    protected function doShow() { ?>
      <div class="span12">

        <!-- TODO remove navigation, not needed on this pages?! -->
        <nav id="nav">
        </nav>

        <h2>Welcome!</h2>

        <section id="welcome">
          <div class="bikemap bikemap-home" data-bikemaptype="exploration" data-bikemapzoom="12"></div>

          <section class="sidebar sidebar-main">
            <article class="recently-stolen box">
              <h2>Recently stolen</h2>
              <ol>
                <li> <a href="">Date, Place, Color etc.</a> </li>
                <li> <a href="">Date, Place, Color etc.</a> </li>
                <li> <a href="">Date, Place, Color etc.</a> </li>
                <li> <a href="">Date, Place, Color etc.</a> </li>
                <li> <a href="">Date, Place, Color etc.</a> </li>
              </ol>
            </article>

            <article class="newest-hints box">
              <h2>Newest Hints</h2>
              <ul>
                <li> <a href="#">Hinthint</a> </li>
                <li> <a href="#">Hinthint</a> </li>
                <li> <a href="#">Hinthint</a> </li>
                <li> <a href="#">Hinthint</a> </li>
              </ul>
            </article>
          </section>

          <section class="sidebar sidebar-second">
            <nav class="nav-other box">
              <h3>More</h3>
              <ul>
                <li><a href="index.php?action=faq">FAQ</a></li>
                <li><a href="index.php?action=about">About</a></li>
                <li><a href="index.php?action=project">Project</a></li>
              </ul>
            </nav>

            <article class="stats box">
              <h3>Stats</h3>
              <table>
                <tr>
                  <td>Bikes returned</td>
                  <td>4</td>
                </tr>
                <tr>
                  <td>Bikes stolen</td>
                  <td>897234</td>
                </tr>
                <tr>
                  <td>Hints given</td>
                  <td>73</td>
                </tr>
              </table>
            </article>
          </section>


          <a href="index.php?action=map" id="btn-start" class="btn btn-large btn-primary">
            Start <i class="icon-white icon-chevron-right"></i>
          </a>

        </section>
      <?php

    }
  }

?>
