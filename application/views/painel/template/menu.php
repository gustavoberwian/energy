<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
				<aside id="sidebar-left" class="sidebar-left">

				    <div class="sidebar-header">
				        <div class="sidebar-toggle d-none d-md-block" data-toggle-class="sidebar-left-collapsed" data-target="html" data-fire-event="sidebar-left-toggle">
				            <i class="fas fa-bars" aria-label="Toggle sidebar"></i>
				        </div>
				    </div>

				    <div class="nano">
				        <div class="nano-content">
				            <nav id="menu" class="nav-main" role="navigation">

				                <ul class="nav nav-main">
				                    <li class="<?php if(in_array($method, array('index'))) echo 'nav-active'; ?>">
				                        <a class="nav-link" href="<?= site_url($this->uri->segment(1)); ?>">
				                            <i class="bx bx-home-alt" aria-hidden="true"></i>
				                            <span>Início</span>
				                        </a>                        
				                    </li>
				                    <li class="<?php if(in_array($method, array('reports'))) echo 'nav-active'; ?>">
				                        <a class="nav-link" href="<?= site_url($this->uri->segment(1).'/reports'); ?>">
				                            <i class="bx bx-file" aria-hidden="true"></i>
				                            <span>Relatórios</span>
				                        </a>
				                    </li>
				                    <li class="<?php if(in_array($method, array('alerts'))) echo 'nav-active'; ?>">
				                        <a class="nav-link" href="<?= site_url($this->uri->segment(1).'/alerts'); ?>">
				                            <i class="bx bx-bell" aria-hidden="true"></i>
				                            <span>Alertas</span>
				                        </a>
				                    </li>
				                </ul>
				            </nav>
				        </div>
				    </div>

				</aside>
