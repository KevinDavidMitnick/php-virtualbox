#!/bin/sh
rbd info cache/vbox &> /dev/null 
if [ ! $? -eq 0 ];then
	rbd create --size 2048G cache/vbox --image-feature layering &> /dev/null
	rbd map cache/vbox &> /dev/null
	mkfs.xfs -q /dev/rbd0 &> /dev/null
	mount -t xfs -o "rw,noexec,nodev,noatime,nodiratime,nobarrier,inode64,logbufs=8,logbsize=256k,allocsize=4M" /dev/rbd0 /home/vbox  &>/dev/null
else 
    df | grep rbd0 &> /dev/null
	if [ ! $? -eq 0 ];then
		ls /dev/rbd0 &> /dev/null
		if [ $? -eq 0 ];then
			mount -t xfs -o "rw,noexec,nodev,noatime,nodiratime,nobarrier,inode64,logbufs=8,logbsize=256k,allocsize=4M" /dev/rbd0 /home/vbox &>/dev/null
                       echo aaa
		else
                      echo bbbb
			rbd map cache/vbox &> /dev/null
			mount -t xfs -o "rw,noexec,nodev,noatime,nodiratime,nobarrier,inode64,logbufs=8,logbsize=256k,allocsize=4M" /dev/rbd0 /home/vbox &> /dev/null
		fi
	fi
fi
