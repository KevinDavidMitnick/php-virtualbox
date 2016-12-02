#!/bin/sh
yum install -y epel-release-7-5.noarch
yum groupinstall 'Development Tools'  -y
yum install -y SDL 
yum install -y dkms
yum install -y kernel-devel-`uname -r`
yum install -y kernel-headers-`uname -r`
yum install -y VirtualBox-5.0
