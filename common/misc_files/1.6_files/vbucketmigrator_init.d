#! /bin/bash 
#
# chkconfig: - 55 45
# description:	vbucketmigrator - memcache connection multiplexer
# processname: vbucketmigrator

# Source function library.
[[ -f /etc/rc.d/init.d/functions ]] && . /etc/rc.d/init.d/functions

PIDFILE="/var/run/${0##*/}".pid

# Check that networking is up.
if [ "$NETWORKING" = "no" ]
then
	exit 0
fi

RETVAL=0
prog="vbucketmigrator"

start () {
    local pid
    # check if it is running
    if [[ -f "$PIDFILE" ]];then
        read pid < "$PIDFILE"
    fi
    if [[ ! -f "$PIDFILE" || -z "$pid" ]];then
        pid=$(pidof /opt/zbase/bin/vbucketmigrator/vbucketmigrator.sh)
    fi
    if [[ -n "$pid" && -d "/proc/$pid" ]];then
      echo "Already running..."
      exit 0
    fi
    /opt/zbase/bin/vbucketmigrator/vbucketmigrator.sh >/dev/null 2>&1 &
    rc=$?
    pidbg=$!
    echo "$pidbg" > "$PIDFILE"

    if [ $rc == 0 ] ; then
      cmd='/bin/true'
    else
      cmd='/bin/false'
    fi
	action $"Starting $prog: " $cmd
}
stop () {
        local pid

	echo -n $"Stopping $prog: "
	killproc -p "$PIDFILE" vbucketmigrator.sh
        
        pid=$(pidof $prog) 
        kill $pid
  	echo
}

restart () {
        stop
        start
}

# See how we were called.
case "$1" in
  start)
	start
	;;
  stop)
	stop
	;;
  status)
	status $prog 
	;;
  restart|reload)
	restart
	;;
  *)
	echo $"Usage: $0 {start|stop|status|stats|restart|reload}"
	exit 1
esac

exit $?
