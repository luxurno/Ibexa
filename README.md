### IBEXA Tech task

### Requirements
Developer TASK
You are required to write a LoadBalancer class(es).
The class has 2 public methods.
The first – the constructor – takes two arguments. 
The first argument is a list of host instances that should be load balanced. 
The second argument is the variant of load balancing algorithm to be used.
There are two variants: the first will simply pass the requests sequentially in rotation to each
of the hosts in the list. 
The second one will either take the first host that has a load under
0.75 or if all hosts in the list are above 0.75, it will take the one with the lowest load.
The second method is called handleRequest(Request $request)and will load balance
the request according to the variant passed on construction.

You can assume that the host instances class has the following API:

public float getLoad()
public void handleRequest(Request $request)
