package utils;

import java.nio.charset.Charset;
import java.util.ArrayList;
import java.util.Collection;
import java.util.List;

import org.apache.tapestry5.OptionGroupModel;
import org.apache.tapestry5.OptionModel;
import org.apache.tapestry5.internal.OptionModelImpl;
import org.apache.tapestry5.util.AbstractSelectModel;

public class SupportedEncodingsSelectModel extends AbstractSelectModel {

	public List<OptionGroupModel> getOptionGroups() {
		return null;
	}

	public List<OptionModel> getOptions() {
		List<OptionModel> optionList = new ArrayList<OptionModel>(0);
		Collection<Charset> charSets = (Charset.availableCharsets()).values();
		for(Object charSet : charSets){
			optionList.add(new OptionModelImpl(charSet.toString(), (Charset)charSet));
		}
		return optionList;
	}
	

}
