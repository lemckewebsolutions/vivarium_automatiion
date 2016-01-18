#!/usr/bin/env python
import sys
import socket
import struct
from time import sleep
 
UDP_IP = "192.168.100.100"
UDP_PORT = 8899
sock = None
 
def send_udp_message(p1, p2, p3):
    hexstring = struct.pack('B', p1) + struct.pack('B', p2) + struct.pack('B', p3)
    print ("Sent UDP command " + str(hex(p1)) + " " + str(hex(p2)) + " " + str(hex(p3)) + " to " + UDP_IP + ":" + str(UDP_PORT))
    sock.sendto(hexstring, (UDP_IP, UDP_PORT))
    return
   
def printAbout():
        print "Usage: milight ZONE COMMAND [PARAMETER]"
        print ""
        print "The ZONE argument specifies which bulb zone the command refers to."
        print "Possible values:"
        print "  ALL/0 - All zones"
        print "  1     - Zone 1"
        print "  2     - Zone 2"
        print "  3     - Zone 3"
        print "  4     - Zone 4"
        print ""
        print "The COMMAND argument specifies the command to be sent to the given bulb zone."
        print "Some commands require a parameter (see below)."
        print "Accepted commands:"
        print "  ON                 - Turn the bulbs in the given zone on."
        print "  OFF                - Turn the bulbs in the given zone off."
        print "  UP/U               - Turn the bulbs in the given zone brightness up."
        print "  DOWN/D             - Turn the bulbs in the given zone brightnessdown."
        print "  WARMER/W           - Turn the bulbs in the given zone warm white increase."
        print "  COOLER/C           - Turn the bulbs in the given zone cool white increase."
        print "  ONFULL/F           - Turn the bulbs in the given zone full brightness."
        print "  NIGHTLIGHT/N       - Turn the bulbs in the given zone nightlight brightness."
        return
 
def sendOnCommand(zone):
    codes = [53, 56, 61, 55, 50];
    send_udp_message(codes[int(zone)], 00, 85);
    return
 
def sendOffCommand(zone):
    codes = [57, 59, 51, 58, 54];
    send_udp_message(codes[int(zone)], 00, 85);
    return
 
def sendWarmerCommand(zone):   
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(62, 00, 85);
    return

def sendCoolerCommand(zone):   
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(63, 00, 85);
    return

def sendBrightnessUpCommand(zone):   
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(60, 00, 85);
    return

def sendBrightnessDownCommand(zone):   
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(52, 00, 85);
    return
 
def sendOnfullCommand(zone): 
    codes = [181, 184, 189, 183, 178];  
    sendOnCommand(zone)
    sleep(0.1)
    send_udp_message(codes[int(zone)], 00, 85);
    return

def sendNightlightCommand(zone): 
    codes = [185, 187, 179, 186, 182];  
    sendOffCommand(zone)
    sleep(0.1)
    send_udp_message(codes[int(zone)], 00, 85);
    return
 
def processCommand(programName, zone, command, param):
    if command == "ON":
        if param != "":
            printAbout()
            return False
        return sendOnCommand(zone)
 
    if command == "OFF":
        if param != "":
            printAbout()
            return False
        return sendOffCommand(zone)
 
    if command == "WARMER" or command == "W":
        if param != "":
            printAbout()
            return False
        return sendWarmerCommand(zone)
 
    if command == "COOLER" or command == "C":
        if param != "":
            printAbout()
            return False
        return sendCoolerCommand(zone)

    if command == "UP" or command == "U":
        if param != "":
            printAbout()
            return False
        return sendBrightnessUpCommand(zone)
 
    if command == "DOWN" or command == "D":
        if param != "":
            printAbout()
            return False
        return sendBrightnessDownCommand(zone)
 
    if command == "ONFULL" or command == "F":
        if param != "":
            printAbout()
            return False
        return sendOnfullCommand(zone)

    if command == "NIGHTLIGHT" or command == "N":
        if param != "":
            printAbout()
            return False
        return sendNightlightCommand(zone)

    printAbout();
    return False        
 
# parse command line options
if len(sys.argv) < 3 or len(sys.argv)>4 :
    printAbout()
    exit(1)
 
programName = str(sys.argv[0])
zone        = str(sys.argv[1]).upper()
command     = str(sys.argv[2]).upper()
param       = ""
 
if len(sys.argv) > 3 :
    param = str(sys.argv[3]).upper()
 
# Connect to inet UDP
sock = socket.socket(socket.AF_INET, socket.SOCK_DGRAM)
 
result = processCommand(programName, zone, command, param)
 
exit(0 if result else 1);
