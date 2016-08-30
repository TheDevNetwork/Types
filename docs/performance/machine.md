
Virtualbox + Vagrant puppetlabs/ubuntu-1604
PHP 7

```
-------------------------------------------------------------------------------

(php -v)

PHP 7.0.9-1+deb.sury.org~xenial+1 (cli) ( NTS )
Copyright (c) 1997-2016 The PHP Group
Zend Engine v3.0.0, Copyright (c) 1998-2016 Zend Technologies
    with Zend OPcache v7.0.9-1+deb.sury.org~xenial+1, Copyright (c) 1999-2016, by Zend Technologies
    with Xdebug v2.4.0, Copyright (c) 2002-2016, by Derick Rethans

(lsb_release -a)
Distributor ID:	Ubuntu
Description:	Ubuntu 16.04 LTS
Release:	16.04
Codename:	xenial

-------------------------------------------------------------------------------

(free)
              total        used        free      shared  buff/cache   available
Mem:        2048524      124908      314400        6084     1609216     1883288
Swap:        524284           0      524284

-------------------------------------------------------------------------------

(lshw -short -C disk)

H/W path        Device     Class      Description
=================================================
/0/2/0.0.0      /dev/sda   disk       21GB VBOX HARDDISK

-------------------------------------------------------------------------------

processor	: 0
vendor_id	: GenuineIntel
cpu family	: 6
model		: 60
model name	: Intel(R) Core(TM) i7-4790K CPU @ 4.00GHz
stepping	: 3
cpu MHz		: 3999.880
cache size	: 8192 KB
physical id	: 0
siblings	: 1
core id		: 0
cpu cores	: 1
apicid		: 0
initial apicid	: 0
fpu		: yes
fpu_exception	: yes
cpuid level	: 13
wp		: yes
flags		: fpu vme de pse tsc msr pae mce cx8 apic sep mtrr pge mca cmov pat pse36 clflush mmx fxsr sse sse2 syscall nx rdtscp lm constant_tsc rep_good nopl xtopology nonstop_tsc pni pclmulqdq monitor ssse3 cx16 sse4_1 sse4_2 movbe popcnt aes xsave avx rdrand hypervisor lahf_lm abm
bugs		:
bogomips	: 7999.76
clflush size	: 64
cache_alignment	: 64
address sizes	: 39 bits physical, 48 bits virtual
power management:

-------------------------------------------------------------------------------

(hdparm -I /dev/sda)

/dev/sda:

ATA device, with non-removable media
	Model Number:       VBOX HARDDISK                           
	Serial Number:      VB2fe50748-7ee5f44b 
	Firmware Revision:  1.0     
Standards:
	Used: ATA/ATAPI-6 published, ANSI INCITS 361-2002 
	Supported: 6 5 4 
Configuration:
	Logical		max	current
	cylinders	16383	16383
	heads		16	16
	sectors/track	63	63
	--
	CHS current addressable sectors:   16514064
	LBA    user addressable sectors:   41943040
	Logical/Physical Sector size:           512 bytes
	device size with M = 1024*1024:       20480 MBytes
	device size with M = 1000*1000:       21474 MBytes (21 GB)
	cache/buffer size  = 256 KBytes (type=DualPortCache)
Capabilities:
	LBA, IORDY(cannot be disabled)
	Standby timer values: spec'd by Vendor, no device specific minimum
	R/W multiple sector transfer: Max = 128	Current = 128
	DMA: mdma0 mdma1 mdma2 udma0 udma1 *udma2 udma3 udma4 udma5 udma6 
	     Cycle time: min=120ns recommended=120ns
	PIO: pio0 pio1 pio2 pio3 pio4 
	     Cycle time: no flow control=120ns  IORDY flow control=120ns
Commands/features:
	Enabled	Supported:
	   *	Power Management feature set
	   *	Write cache
	   *	Look-ahead
	   *	Mandatory FLUSH_CACHE
HW reset results:
	CBLID- above Vih
	Device num = 0 determined by the jumper
Checksum: correct
```
